<?php

namespace evolun\event\modules\description;

use Yii;

/**
 * Gyerekhez tartozó dokumentumok modul
 */
class Module extends \evolun\event\modules\EventSubModule
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'evolun\event\modules\description\controllers';

    /**
     * Az események modelje
     * @var string
     */
    public $eventModelClass = 'evolun\event\models\Event';

    public function init()
    {
        parent::init();

        if (!$this->title) {
            $this->title = Yii::t('event', 'Description');
        }
    }
}
