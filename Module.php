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
     * Az esemény kategóriák egyes tulajdonságai (alapértelmezett kategóriák)
     * A tömb kulcsa a kategória adatbázisban rögzített neve, a tömb elemei:
     * - title: A kategória neve
     * - icon: ez jelenik meg a felületen mindenhol a kategória neve mellett
     * - color: az ikon szine
     * - roles: szerepkörök, amikre lehet jelentkezni adott kategórián belül
     *          (tömb, a kulcs az adatbázisban tárolt adat, az érték szintén tömb, elemei:
     *          name: a szerep neve, limit: hányan jelentkezhetnek (0: végtelen))
     * @var array
     */
    private $defaultCategories;

    /**
     * A saját esemény kategóriák (@see defaultCategories)
     * @var array
     */
    public $categories = [];

    /**
     * Az esemény adatlapjának bal oldalán megjelenő dobozok (a fő doboz alatt)
     * @var array
     */
    public $widgets = [
        \evolun\event\widgets\RelatedEventsWidget::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if (!Yii::$app->user->identity instanceof \evolun\user\models\User) {
            throw new \yii\base\InvalidConfigException('You have to install \'evolun-user\' to use this module');
        }

        $this->registerTranslations();

        $this->defaultCategories = [
            'study' => ['title' => Yii::t('event', 'Study'), 'icon' => 'study.svg', 'color' => '#4B85C2'],
            'meeting' => ['title' => Yii::t('event', 'Meeting'), 'icon' => 'meeting.svg', 'color' => '#F2A033'],
            'party' => ['title' => Yii::t('event', 'Party'), 'icon' => 'party.svg', 'color' => '#BAE324'],
            'camp' => ['title' => Yii::t('event', 'Camp'), 'icon' => 'camp.svg', 'color' => '#E94150'],
            'hike' => ['title' => Yii::t('event', 'Hike'), 'icon' => 'hike.svg', 'color' => '#6EE2C7'],
        ];

        $this->categories = ArrayHelper::merge($this->defaultCategories, $this->categories);
    }

    public function registerTranslations()
    {
        if (!isset(Yii::$app->get('i18n')->translations['event'])) {
            Yii::$app->get('i18n')->translations['event*'] = [
                'class' => \yii\i18n\PhpMessageSource::className(),
                'basePath' => __DIR__ . '/messages',
                'sourceLanguage' => 'en-US',
                'fileMap' => [
                    'event' => 'event.php',
                ]
            ];
        }
    }
}
