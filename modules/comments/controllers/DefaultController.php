<?php

namespace evolun\event\modules\comments\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use evolun\event\modules\comments\models\EventComment;

class DefaultController extends Controller
{
    /**
     * Az esemény modelje
     * @var Event
     */
    private $_event;

    /**
     * {@inheritdoc}
     */
    public function init() : void
    {
        $this->setEvent($this->module->getEvent());
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors() : array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'new-comment' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'comments', 'new-comment', 'delete'],
                        'allow'   => true,
                        'roles'   => ['showEvents'],
                    ],
                ]
            ],
        ];
    }

    /**
     * Rendereli a base view-t
     * @param  int $id Az esemény id-ja
     * @return string
     */
    public function actionIndex(int $id) : string
    {
        $count = EventComment::find()->where(['event_id' => $id])->count();
        if ($count) {
            $this->module->title .= ' (' . $count . ')';
        }
        $model = new EventComment(['event_id' => $id]);

        return $this->renderPartial('index', [
            'model' => $model
        ]);
    }

    /**
     * Töröl egy megjegyzést
     * A törlendő megjegyzés id-t $_POST-ban várja
     * JSON response
     * @param  int    $id A gyerek id-ja
     * @return array
     */
    public function actionDelete(int $id) : array
    {
        Yii::$app->response->format = 'json';

        $model = EventComment::findOne([
            'user_id' => Yii::$app->user->id,
            'event_id' => $id,
            'id' => Yii::$app->request->post('comment_id')
            ]);

        if ($model && $model->delete()) {
            return ['success' => 1];
        } else {
            return ['success' => 0];
        }
    }

    /**
     * Létrehoz egy megjegyzést
     * JSON response
     * @param  int $id Az esemény id-ja
     * @return array
     */
    public function actionNewComment($id) : array
    {
        Yii::$app->response->format = 'json';

        $model = new EventComment(['event_id' => $id, 'user_id' => Yii::$app->user->id]);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->save()) {
                return ['success' => 1];
            } else {
                return ['success' => 0, 'error' => $model->getErrorSummary(false)];
            }
        }
    }

    /**
     * Visszaadja a kommenteket
     * JSON response
     * @param  int $id Az esemény id-ja
     * @return array
     */
    public function actionComments($id) : array
    {
        Yii::$app->response->format = 'json';

        $comments = EventComment::find()
            ->where(['event_id' => $id])
            ->orderBy('date DESC')
            ->all();
        $return = [];

        foreach ($comments as $comment) {
            $return[] = array_merge($comment->toArray(), [
                'user' => [
                    'name' => $comment->user->name,
                    'nickname' => $comment->user->nickname,
                    'image' => $comment->user->getThumbUploadUrl('image', 's'),
                    'url' => Url::to(['/user/default/view', 'id' => $comment->user->id]),
                ],
                'current_user_id' => Yii::$app->user->id,
            ]);
        }

        return $return;
    }

    private function setEvent($event) : void
    {
        $this->_event = $event;
    }

    private function getEvent()
    {
        return $this->_event;
    }
}
