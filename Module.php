<?php

namespace evolun\event;

use yii;

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
    private $defaultCategories = [
        'study' => ['title' => 'Foglalkozás', 'icon' => 'study.svg', 'color' => '#4B85C2'],
        'meeting' => ['title' => 'Megbeszélés', 'icon' => 'meeting.svg', 'color' => '#F2A033'],
        'party' => ['title' => 'Találkozó', 'icon' => 'party.svg', 'color' => '#BAE324'],
        'camp' => ['title' => 'Tábor', 'icon' => 'camp.svg', 'color' => '#E94150'],
        'hike' => ['title' => 'Túra', 'icon' => 'hike.svg', 'color' => '#6EE2C7']
    ];

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

        $this->categories = array_merge($this->defaultCategories, $this->categories);

        if (!class_exists(Yii::$app->user->identityClass)) {
            throw new \yii\base\InvalidConfigException('Nem található a felhasználó modul, ami elengedhetetlen az esemény modulhoz');
        }
    }
}
