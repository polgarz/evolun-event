<?php
namespace evolun\event\widgets;

use Yii;
use evolun\event\models\Event;
use yii\data\ActiveDataProvider;

class RecentEventsWidget extends \yii\base\Widget
{
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
                ->with('participates')
                ->where(['>', 'start', new yii\db\Expression('NOW()')])
                ->orderBy('start ASC')
                ->limit(3),
            'pagination' => false
        ]);

        return $this->render('recent-event-list', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
