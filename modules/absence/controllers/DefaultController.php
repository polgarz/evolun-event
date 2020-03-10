<?php

namespace evolun\event\modules\absence\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use evolun\event\modules\absence\models\Absence;

class DefaultController extends Controller
{
    /**
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
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['delete', 'create'],
                        'allow'   => true,
                        'roles'   => ['manageEvents'],
                        'roleParams' => function ($rule) {
                            return ['event' => $this->getEvent()];
                        }
                    ],
                    [
                        'actions' => ['index', 'absences', 'kids'],
                        'allow'   => true,
                        'roles'   => ['showEvents'],
                    ],
                ]
            ],
        ];
    }

    public function actionIndex(int $id) : string
    {
        $model = new Absence(['event_id' => $id]);

        $count = Absence::find()->where(['event_id' => $id])->count();
        if ($count) {
            $this->module->title .= ' (' . $count . ')';
        }

        return $this->renderPartial('index', [
            'event' => $this->getEvent(),
            'model' => $model,
        ]);
    }

    /**
     * List all absences
     * @param  int    $id Event id
     * @return array (JSON response)
     */
    public function actionAbsences(int $id) : array
    {
        Yii::$app->response->format = 'json';

        $absences = Absence::find()
            ->where(['event_id' => $id])
            ->all();
        $return = [];

        foreach ($absences as $absence) {
            $return[] = [
                'id' => $absence->kid_id,
                'name' => $absence->kid->name,
                'family' => $absence->kid->family,
                'url' => Url::to(['/kid/default/view', 'id' => $absence->kid_id]),
                'image' => $absence->kid->getThumbUploadUrl('image', 's'),
                'reason' => $absence->reason,
            ];
        }

        return $return;
    }

    /**
     * Delete a Absence model
     * @param  int    $id Event id
     * @return array (JSON response)
     */
    public function actionDelete(int $id) : array
    {
        Yii::$app->response->format = 'json';

        $model = Absence::findOne([
            'event_id' => $id,
            'kid_id' => Yii::$app->request->post('kid_id')
            ]);

        if ($model && $model->delete()) {
            return ['success' => 1];
        } else {
            return ['success' => 0];
        }
    }

    /**
     * Creates new Absence model
     * @param  int    $id Event id
     * @return array (JSON response)
     */
    public function actionCreate(int $id) : array
    {
        Yii::$app->response->format = 'json';

        $model = new Absence(['event_id' => $id]);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->save()) {
                return ['success' => 1];
            } else {
                return ['success' => 0, 'error' => $model->getErrorSummary(false)];
            }
        }
    }

    /**
     * Lists all kids (JSON)
     * @param  int    $id Event id
     * @return array (JSON response)
     */
    public function actionKids(int $id) : array
    {
        Yii::$app->response->format = 'json';

        $kid = Yii::createObject($this->module->kidModelClass);

        $query = $kid::find()
            ->select('name, family, kid.id')
            ->where(['inactive' => 0])
            ->joinWith('kidGroupKids')
            ->orderBy('name');

        $query->andFilterWhere(['in', 'kid_group_id', $this->module->kidGroups]);

        return $query->all();
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
