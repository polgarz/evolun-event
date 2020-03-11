<?php
namespace evolun\event\widgets;

use Yii;
use evolun\event\modules\absence\models\Absence;
use evolun\kid\models\Kid;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

class AbsencesWidget extends \yii\base\Widget
{
    /**
     * @var string
     */
    public $eventModuleId = 'event';

    /**
     * @var DateTime
     */
    public $from;

    /**
     * @var DateTime
     */
    public $to;

    /**
     * @var int
     */
    public $threshold;

    /**
     * @var string
     */
    public $title;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if (!$this->title) {
            $this->title = Yii::t('event/widget', 'Absences');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if (!Yii::$app->user->can('showKids')) {
            return null;
        }

        $query = Absence::find()
            ->asArray()
            ->select(['kid_id', 'event_id', 'COUNT(*) AS absences'])
            ->joinWith(['kid', 'event'])
            ->where(['inactive' => 0])
            ->groupBy('kid_id')
            ->orderBy('absences DESC');

        if ($this->threshold) {
            $query->having('COUNT(*) >= :threshold', ['threshold' => $this->threshold]);
        }

        if ($this->from) {
            $query->andWhere(['>', 'start', $this->from->format('Y-m-d')]);
        }

        if ($this->to) {
            $query->andWhere(['<', 'start', $this->to->format('Y-m-d')]);
        }

        $dataProvider = new ArrayDataProvider([
            'models' => $query->all(),
            'pagination' => false
        ]);

        $kids = Kid::find()
            ->indexBy('id')
            ->where(['in', 'id', ArrayHelper::getColumn($dataProvider->models, 'kid_id')])
            ->all();

        return $this->render('absences', [
            'dataProvider' => $dataProvider,
            'kids' => $kids,
            'eventModuleId' => $this->eventModuleId,
            'title' => $this->title,
        ]);
    }
}
