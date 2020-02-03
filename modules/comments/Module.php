<?php

namespace evolun\event\modules\comments;

use Yii;

/**
 * Gyerekhez tartozó dokumentumok modul
 */
class Module extends \evolun\event\modules\EventSubModule
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'evolun\event\modules\comments\controllers';

    /**
     * Az események modelje
     * @var string
     */
    public $eventModelClass = 'evolun\event\models\Event';

    public function init()
    {
        parent::init();
    }
}
