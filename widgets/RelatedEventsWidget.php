<?php
namespace evolun\event\widgets;

use Yii;
use evolun\event\models\Event;
use yii\data\ActiveDataProvider;

class RelatedEventsWidget extends \yii\base\Widget implements EventWidgetInterface
{
    /**
     * @var Event
     */
    public $event;

    /**
     * @var string
     */
    public $eventModuleId = 'event';

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Event::find()
                ->with('participates')
                ->where(['>', 'start', new yii\db\Expression('NOW()')])
                ->andWhere(['category' => $this->event->category])
                ->andWhere(['!=', 'id', $this->event->id])
                ->orderBy('start ASC')
                ->limit(3),
            'pagination' => false
        ]);

        return $this->render('related-event-list', [
            'dataProvider' => $dataProvider,
            'eventModuleId' => $this->eventModuleId,
        ]);
    }

    public function getEvent()
    {
        return $this->event;
    }
}
