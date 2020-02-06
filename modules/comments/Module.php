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

    /**
     * Comment notification email
     * @var string
     */
    public $commentNotificationEmail = '@vendor/polgarz/evolun-event/modules/comments/mail/{language}/new-comment';

    public function init()
    {
        parent::init();

        $this->commentNotificationEmail = strtr($this->commentNotificationEmail, ['{language}' => Yii::$app->language]);

        if (!$this->title) {
            $this->title = Yii::t('event', 'Comments');
        }
    }
}
