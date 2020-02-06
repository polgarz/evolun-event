<?php
namespace evolun\event\widgets;

use Yii;
use evolun\event\models\Event;
use yii\data\ActiveDataProvider;

class MyEventsWidget extends \yii\base\Widget
{
    /**
     * @var string
     */
    public $eventModuleId = 'event';

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if (!Yii::$app->user->can('showEvents')) {
            return null;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Event::find()
                ->joinWith('participates')
                ->where(['user_id' => Yii::$app->user->id])
                ->orderBy('start DESC')
                ->limit(3),
            'pagination' => false
        ]);

        return $this->render('my-event-list', [
            'dataProvider' => $dataProvider,
            'eventModuleId' => $this->eventModuleId,
        ]);
    }
}
