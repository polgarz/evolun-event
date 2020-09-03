<?php

namespace evolun\event;

use Yii;
use yii\helpers\ArrayHelper;

class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'evolun\event\controllers';

    /**
     * Custom event categories (for schema @see defaultCategories)
     * @var array
     */
    public $categories = [];

    /**
     * Widgets (below the main box on event page)
     * @var array
     */
    public $widgets = [
        \evolun\event\widgets\RelatedEventsWidget::class,
    ];

    /**
     * Default event categories
     * The key of the array is the name of the category recorded in the database,
     * the elements of the array are:
     * - title: category title
     * - icon: icon url (for example: /icons/hike.svg)
     * - color: icon background color
     * - roles: roles that can be applied for within a given category
     *   array, key of the array is the name of the category recorded in the database,
     *     - name: role name
     *     - limit: maximum person who can apply (0: infinity))
     * @var array
     */
    private $_defaultCategories;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if (Yii::$app->user->identity && !Yii::$app->user->identity instanceof \evolun\user\models\User) {
            throw new \yii\base\InvalidConfigException('You have to install \'evolun-user\' to use this module');
        }

        $this->registerTranslations();

        $this->_defaultCategories = [
            'study' => ['title' => Yii::t('event', 'Study'), 'icon' => 'study.svg', 'color' => '#4B85C2'],
            'meeting' => ['title' => Yii::t('event', 'Meeting'), 'icon' => 'meeting.svg', 'color' => '#F2A033'],
            'party' => ['title' => Yii::t('event', 'Party'), 'icon' => 'party.svg', 'color' => '#BAE324'],
            'camp' => ['title' => Yii::t('event', 'Camp'), 'icon' => 'camp.svg', 'color' => '#E94150'],
            'hike' => ['title' => Yii::t('event', 'Hike'), 'icon' => 'hike.svg', 'color' => '#6EE2C7'],
        ];

        $this->categories = ArrayHelper::merge($this->_defaultCategories, $this->categories);
    }

    public function registerTranslations()
    {
        if (!isset(Yii::$app->get('i18n')->translations['event'])) {
            Yii::$app->get('i18n')->translations['event*'] = [
                'class' => \yii\i18n\PhpMessageSource::class,
                'basePath' => __DIR__ . '/messages',
                'sourceLanguage' => 'en-US',
                'fileMap' => [
                    'event' => 'event.php',
                ]
            ];
        }
    }
}
