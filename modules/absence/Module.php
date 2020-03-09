<?php

namespace evolun\event\modules\absence;

use Yii;

/**
 * Absences module
 */
class Module extends \evolun\event\modules\EventSubModule
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'evolun\event\modules\absence\controllers';

    /**
     * Event model
     * @var string
     */
    public $eventModelClass = 'evolun\event\models\Event';

    /**
     * Kids model
     * @var string
     */
    public $kidModelClass = 'evolun\kid\models\Kid';

    /**
     * Kid group IDs
     * Only the kids who are in these groups appear in the list of possible absences
     * @var array
     */
    public $kidGroups = [];

    public function init()
    {
        parent::init();

        if (!class_exists($this->kidModelClass)) {
            throw new InvalidConfigException(Yii::t('event/absence', 'You have to install \'evolun-kid\' to use this module.'));
        }

        if (!$this->title) {
            $this->title = Yii::t('event', 'Absences');
        }
    }
}
