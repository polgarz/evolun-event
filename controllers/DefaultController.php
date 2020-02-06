<?php

namespace evolun\event\controllers;

use Yii;
use evolun\event\models\{Event, EventParticipate, EventParticipateDays};
use evolun\event\modules\EventSubModule;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * DefaultController implements the CRUD actions for Event model.
 */
class DefaultController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'attend' => ['POST'],
                    'cancel-attend' => ['POST'],
                    'set-organizer' => ['POST'],
                    'set-attend-options' => ['POST']
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow'   => true,
                        'roles'   => ['manageEvents'],
                    ],
                    [
                        'actions' => ['index', 'view', 'attend', 'cancel-attend', 'set-organizer', 'set-attend-options'],
                        'allow'   => true,
                        'roles'   => ['showEvents'],
                    ],
                ]
            ],
        ];
    }

    /**
     * Kilistázza az eseményeket
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Event::find()
                ->with('participates')
                ->where(['<', 'start', new \yii\db\Expression('NOW() + INTERVAL 2 MONTH')])
                ->orderBy('start DESC'),
            'pagination' => [
                'defaultPageSize' => 50
            ]
        ]);

        $closestEvent = Event::find()
            ->where(['>', 'end', new \yii\db\Expression('NOW()')])
            ->orderBy('end ASC')
            ->limit(1)
            ->one();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'closestEvent' => $closestEvent
        ]);
    }

    /**
     * Egy esemény adatlapja
     * @param integer $id A esemény id-ja
     * @return string
     * @throws NotFoundHttpException ha nem létező esemény id-t kap
     */
    public function actionView($id) : string
    {
        $modules = [];
        $model = $this->findModel($id);
        $attendance = $model->getParticipates()->where(['user_id' => Yii::$app->user->id])->one();

        if ($this->module->modules) {
            foreach($this->module->modules as $id => $module) {
                $module = $this->module->getModule($id);

                if (!$module instanceof EventSubModule) {
                    continue;
                }

                if (count($module->allowedCategoryIds)
                    && !in_array($model->category, $module->allowedCategoryIds)) {
                    continue;
                }

                $content = $module->runAction($module->defaultRoute, Yii::$app->request->get());

                $modules[] = [
                    'title' => $module->title ?? 'Module',
                    'content' => $content
                ];
            }
        }

        return $this->render('view', [
            'model' => $model,
            'modules' => $modules,
            'attendance' => $attendance,
        ]);
    }

    /**
     * Részvétel bejegyzése egy eseményhez
     * @param integer $id A esemény id-ja
     * @return mixed
     * @throws NotFoundHttpException ha nem létező esemény id-t kap
     */
    public function actionAttend($id)
    {
        $event = $this->findModel($id);
        $model = new EventParticipate(['event_id' => $event->id, 'user_id' => Yii::$app->user->id]);

        // ha veget ert az esemeny, nem csinalunk semmit
        if ($event->end < date('Y-m-d H:i:s')) {
            Yii::$app->session->setFlash('danger', Yii::t('event', 'You can\'t apply to this event, because it\'s over'));
            return $this->redirect(['view', 'id' => $event->id]);
        }

        // ha tartoznak az esemeny kategoriahoz szerepek, akkor az elsot beallitjuk
        if (isset($event->categoryDetails['roles'])) {
            // ha az elso lehetseges opcio mar betelt, akkor lepunk a kovetkezore.. es igy tovabb
            foreach($event->categoryDetails['roles'] as $id => $role) {
                if (!$role['limit'] || $role['limit'] > count($event->participatesByRole[$id] ?? [])) {
                    $model->role = $id;
                    break;
                }
            }
        }

        if ($model->save()) {
            // ha több napos az esemény, akkor megelőlegezzük, hogy mindegyik napon ott lesz a felhasználó
            if (count($event->days) > 1) {
                foreach($event->days as $day) {
                    $day = new EventParticipateDays(['event_participate_id' => $model->id, 'date' => $day->format('Y-m-d')]);
                    $day->save();
                }
            }
            Yii::$app->session->setFlash('success', Yii::t('event', 'You successfully applied to this event'));
        } else {
            Yii::$app->session->setFlash('danger', Yii::t('event', 'Something went wrong with the application'));
        }

        return $this->redirect(['view', 'id' => $event->id]);
    }

    /**
     * Beállítja a részvételi opciókat (napok száma, szerep)
     * JSON response
     * @param  int $id Az esemény id-ja
     * @return array
     * @throws NotFoundHttpException ha nem létező esemény id-t kap
     */
    public function actionSetAttendOptions($id)
    {
        Yii::$app->response->format = 'json';

        $event = $this->findModel($id);
        $model = EventParticipate::findOne(['event_id' => $event->id, 'user_id' => Yii::$app->user->id]);

        if ($model) {
            $role = Yii::$app->request->post('role');
            $days = Yii::$app->request->post('days', []);

            // ha szerepet valt
            if ($role && array_key_exists($role, ($event->categoryDetails['roles'] ?? []))) {
                $model->role = $role;
                if ($model->save()) {
                    return [
                        'success' => 1,
                        'selectedRole' => $event->categoryDetails['roles'][$role]['name'],
                    ];
                } else {
                    return ['success' => 0, 'error' => $model->errors];
                }
            }

            // ha napot valt
            if (count($days) > 0) {
                EventParticipateDays::deleteAll(['event_participate_id' => $model->id]);
                foreach($days as $day) {
                    $eventParticipateDay = new EventParticipateDays(['event_participate_id' => $model->id, 'date' => $day]);
                    $eventParticipateDay->save();
                }
                return ['success' => 1];
            }
        }

        return ['success' => 0];
    }

    /**
     * Részvétel visszavonása egy eseményhez
     * @param integer $id A esemény id-ja
     * @return mixed
     */
    public function actionCancelAttend($id)
    {
        $model = EventParticipate::findOne(['event_id' => $id, 'user_id' => Yii::$app->user->id]);
        $event = $this->findModel($id);

        // ha veget ert az esemeny, nem csinalunk semmit
        if ($event->end < date('Y-m-d H:i:s')) {
            Yii::$app->session->setFlash('danger', Yii::t('event', 'You can\'t cancel the application beacuse the event is over!'));
            return $this->redirect(['view', 'id' => $event->id]);
        }

        if ($model && $model->delete()) {
            Yii::$app->session->setFlash('success', Yii::t('event', 'Successfully canceled the event'));
        } else {
            Yii::$app->session->setFlash('danger', Yii::t('event', 'Something went wrong when tried to cancel the application'));
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Beállítja a felhasználót mint az esemény szervezője
     * @param integer $id A esemény id-ja
     * @param integer $cancel Ha 1, akkor leszedi a felhasználót
     * @return mixed
     * @throws NotFoundHttpException ha nem létező esemény id-t kap
     */
    public function actionSetOrganizer($id, $cancel = 0)
    {
        $model = $this->findModel($id);
        if ($cancel) {
            $model->organizer_user_id = null;
        } else {
            $model->organizer_user_id = Yii::$app->user->id;
        }

        if ($model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('event', 'Successfully applied as an organizer'));
        } else {
            Yii::$app->session->setFlash('danger', Yii::t('event', 'Something went wrong with the application as an organizer'));
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Esemény létrehozása, ha sikeres, az adatlapra ugrik
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Event();
        $userList = $this->getUserList();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('event', 'Create succesful'));
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('danger', Yii::t('event', 'Create unsuccesful'));
            }
        }

        return $this->render('create', [
            'model' => $model,
            'userList' => $userList,
        ]);
    }

    /**
     * Esemény adatainak módosítása, ha sikeres, az adatlapra ugrik
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException ha nem létező esemény id-t kap
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $userList = $this->getUserList();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('event', 'Update succesful'));
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('danger', Yii::t('event', 'Update unsuccesful'));
            }
        }

        return $this->render('update', [
            'model' => $model,
            'userList' => $userList,
        ]);
    }

    /**
     * Töröl egy esemény modelt, ha sikeres, a listára ugrik
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException ha nem létező esemény id-t kap
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Visszaadja a felhasznalok listajat
     * @return array
     */
    protected function getUserList()
    {
        $userModel = Yii::$app->user->identityClass;

        return $userModel::find()
            ->select('name')
            ->indexBy('id')
            ->orderBy('name')
            ->asArray()
            ->column();
    }

    /**
     * Megkeres egy esemény modelt
     * @param integer $id
     * @return Event
     * @throws NotFoundHttpException ha nem létező esemény id-t kap
     */
    protected function findModel($id)
    {
        if (($model = Event::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
    }
}
