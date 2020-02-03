<?php

namespace evolun\event\modules;

use Yii;
use evolun\event\models\Event;

/**
 * Esemeényekhez tartozó al modulok fő modulja
 */
class EventSubModule extends \yii\base\Module
{
    /**
     * A modul neve (ami megjelenik a tabon is)
     * @var string
     */
    public $title;

    /**
     * Csak a tömbben szereplő esemény kategóriákban lévő eseményeknél jelenik meg a modul
     * @var array
     */
    public $allowedCategoryIds = [];

    /**
     * Az esemény modelje
     * @var Event
     */
    private $_event;

    public function getEvent()
    {
        return $this->_event;
    }

    public function setEvent($event)
    {
        $this->_event = $event;
    }

    public function init()
    {
        // beallitjuk az eseményt
        if ($event = Event::findOne(Yii::$app->request->get('id'))) {
            $this->setEvent($event);
        } else {
            throw new NotFoundHttpException('Nincs ilyen esemény!');
        }

        parent::init();
    }
}
