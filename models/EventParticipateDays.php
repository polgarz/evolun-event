<?php

namespace evolun\event\models;

use Yii;

/**
 * This is the model class for table "event_participate_days".
 *
 * @property int $id
 * @property int $event_participate_id
 * @property string $date
 *
 * @property EventParticipate $eventParticipate
 */
class EventParticipateDays extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event_participate_days';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_participate_id', 'date'], 'required'],
            [['event_participate_id'], 'integer'],
            [['date'], 'safe'],
            [['event_participate_id'], 'exist', 'skipOnError' => true, 'targetClass' => EventParticipate::className(), 'targetAttribute' => ['event_participate_id' => 'id']],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventParticipate()
    {
        return $this->hasOne(EventParticipate::className(), ['id' => 'event_participate_id']);
    }
}
