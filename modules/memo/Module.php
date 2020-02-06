<?php

namespace evolun\event\modules\memo;

use Yii;

/**
 * Eseményekhez tartozó memo modul
 */
class Module extends \evolun\event\modules\EventSubModule
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'evolun\event\modules\memo\controllers';

    /**
     * Az események modelje
     * @var string
     */
    public $eventModelClass = 'evolun\event\models\Event';

    public function init()
    {
        parent::init();

        if (!$this->title) {
            $this->title = Yii::t('event', 'Memo');
        }
    }
}
