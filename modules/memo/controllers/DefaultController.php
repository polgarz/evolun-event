<?php

namespace evolun\event\modules\memo\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use evolun\event\modules\memo\models\EventMemo;

class DefaultController extends Controller
{
    /**
     * @var Event
     */
    private $_event;

    /**
     * Maximum ennyi memot tarolunk
     */
    const MEMOS_MAX_COUNT = 10;

    /**
     * {@inheritdoc}
     */
    public function init() : void
    {
        parent::init();

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
                    'save' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['save', 'history'],
                        'allow'   => true,
                        'roles'   => ['manageEvents'],
                        'roleParams' => function ($rule) {
                            return ['event' => $this->getEvent()];
                        }
                    ],
                    [
                        'actions' => ['index'],
                        'allow'   => true,
                        'roles'   => ['showEvents'],
                    ],
                ]
            ],
        ];
    }

    /**
     * Rendereli a base view-t
     * @param  int $id Az esemeny id-ja
     * @return string
     */
    public function actionIndex(int $id) : string
    {
        $memo = EventMemo::find()
            ->where(['event_id' => $id])
            ->orderBy('created_at DESC')
            ->limit(1)
            ->one();

        return $this->renderPartial('index', [
            'event' => $this->getEvent(),
            'memo' => $memo,
        ]);
    }

    /**
     * Visszaadja az esemenyhez tartozo memokat csokkeno idorendi sorrendben (az elso az aktualis)
     * @param  int    $id Esemeny id
     * @return array
     */
    public function actionHistory(int $id) : array
    {
        Yii::$app->response->format = 'json';

        return EventMemo::find()
            ->where(['event_id' => $id])
            ->orderBy('created_at DESC')
            ->all();
    }

    /**
     * Elmenti a memot
     * @param  int    $id Esemeny id
     * @return array
     */
    public function actionSave(int $id) : array
    {
        Yii::$app->response->format = 'json';

        $model = new EventMemo(['event_id' => $id]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // ha tobb memo van, mint amennyit megadtunk, a regieket toroljuk
            $memos = EventMemo::find()
                ->where(['event_id' => $id])
                ->offset(self::MEMOS_MAX_COUNT)
                ->orderBy('created_at DESC')
                ->all();

            foreach ($memos as $memo) {
                $memo->delete();
            }

            return ['success' => 1];
        } else {
            return ['success' => 0];
        }
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
