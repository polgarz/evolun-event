<?php

namespace evolun\event\modules\description\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

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
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
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
     * @param  int $id Az esemény id-ja
     * @return string
     */
    public function actionIndex(int $id) : string
    {
        return $this->renderPartial('index', [
            'description' => $this->getEvent()->description,
        ]);
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
