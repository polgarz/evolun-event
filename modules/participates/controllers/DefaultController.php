<?php

namespace evolun\event\modules\participates\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use evolun\event\models\EventParticipate;

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
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['delete'],
                        'allow'   => true,
                        'roles'   => ['manageEvents'],
                        'roleParams' => function($rule) {
                            return ['event' => $this->getEvent()];
                        }
                    ],
                    [
                        'actions' => ['index', 'participates'],
                        'allow'   => true,
                        'roles'   => ['showEvents'],
                    ],
                ]
            ],
        ];
    }

    /**
     * Rendereli a base view-t
     * @param  int $id A gyerek id-ja
     * @return string
     */
    public function actionIndex(int $id) : string
    {
        $model = new EventParticipate(['event_id' => $id]);

        return $this->renderPartial('index', [
            'event' => $this->getEvent(),
            'model' => $model,
        ]);
    }

    /**
     * Töröl egy résztvevőt az eseményről
     * A törlendő user id-t $_POST-ban várja
     * JSON response
     * @param  int    $id Az esemény id-ja
     * @return array
     */
    public function actionDelete(int $id) : array
    {
        Yii::$app->response->format = 'json';

        $model = EventParticipate::findOne(['event_id' => $id, 'user_id' => Yii::$app->request->post('user_id')]);

        if ($model && $model->delete()) {
            return ['success' => 1];
        } else {
            return ['success' => 0];
        }
    }

    /**
     * Visszaadja a résztvevőket
     * JSON response
     * @param  int $id Az esemény id-ja
     * @return array
     */
    public function actionParticipates($id) : array
    {
        Yii::$app->response->format = 'json';

        $event = $this->getEvent();
        $participates = $event->participates;
        $roles = $event->categoryDetails['roles'] ?? [];
        $return = [
            'items' => [],
            'users' => $this->getUserList(),
        ];

        foreach($participates as $participate) {

            $user = $participate->user;
            $authRole = Yii::$app->authManager->getRole($user->role);

            if ($roles && array_key_exists($participate->role, $roles)) {
                $roleName = $roles[$participate->role]['name'];
            } else {
                $roleName = '';
            }

            $summary = [];

            if ($relativeMemberSince = $user->getRelativeMemberSince(true)) {
                $summary[] = $relativeMemberSince . ' tag';
            }

            if ($authRole) {
                $summary[] = $authRole->description;
            }

            if ($participate->user_id === $event->organizer_user_id) {
                $summary[] = 'szervező';
            }

            $whole = count($participate->days) === count($event->days);
            $days = [];
            if (!$whole) {
                foreach ($participate->days as $day) {
                    $days[] = Yii::$app->formatter->asDate($day->date, 'MMM dd. (EE)');
                }
            }

            $item = [
                'id' => $participate->id,
                'user_id' => $participate->user_id,
                'name' => $user->name,
                'nickname' => $user->nickname,
                'days' => implode(', ', $days),
                'whole' => $whole,
                'image' => $user->getThumbUploadUrl('image', 's'),
                'url' => Url::to(['/user/default/view', 'id' => $user->id]),
                'summary' => implode(', ', $summary),
            ];

            $return['items'][$roleName][] = $item;
        }

        return $return;
    }

    /**
     * Visszaadja a felhasznalok listajat
     * @return array
     */
    protected function getUserList()
    {
        $userModel = Yii::$app->user->identityClass;

        return $userModel::find()
            ->select('name, id')
            ->orderBy('name')
            ->asArray()
            ->all();
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