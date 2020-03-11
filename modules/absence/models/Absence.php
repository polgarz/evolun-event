<?php

namespace evolun\event\modules\absence\models;

use Yii;
use evolun\event\models\Event;
use evolun\kid\models\Kid;

/**
 * This is the model class for table "absence".
 *
 * @property int $id
 * @property int $event_id
 * @property int $kid_id
 * @property string|null $reason
 *
 * @property Event $event
 * @property Kid $kid
 */
class Absence extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kid_absence';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_id', 'kid_id'], 'required'],
            [['event_id', 'kid_id'], 'integer'],
            [['reason'], 'string', 'max' => 255],
            ['kid_id', 'unique', 'targetAttribute' => ['kid_id', 'event_id'], 'message' => Yii::t('event/absence', 'This kid was already set as absent before')],
            [['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => Event::class, 'targetAttribute' => ['event_id' => 'id']],
            [['kid_id'], 'exist', 'skipOnError' => true, 'targetClass' => Kid::class, 'targetAttribute' => ['kid_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'reason' => Yii::t('event/absence', 'Reason'),
        ];
    }

    /**
     * Gets query for [[Event]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Event::class, ['id' => 'event_id']);
    }

    /**
     * Gets query for [[Kid]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKid()
    {
        return $this->hasOne(Kid::class, ['id' => 'kid_id']);
    }
}
