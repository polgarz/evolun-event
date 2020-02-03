<?php

namespace evolun\event\modules\participates;

use Yii;

/**
 * Gyerekhez tartozó dokumentumok modul
 */
class Module extends \evolun\event\modules\EventSubModule
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'evolun\event\modules\participates\controllers';

    /**
     * Az események modelje
     * @var string
     */
    public $eventModelClass = 'evolun\event\models\Event';
}
