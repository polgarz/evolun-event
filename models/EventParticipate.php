<?php

namespace evolun\event\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "event_participate".
 *
 * @property int $id
 * @property int $user_id
 * @property int $event_id
 * @property string|null $role
 *
 * @property Event $event
 * @property User $user
 * @property EventParticipateDays[] $eventParticipateDays
 */
class EventParticipate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event_participate';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'event_id'], 'required'],
            [['user_id', 'event_id'], 'integer'],
            [['role'], 'string', 'max' => 50],
            ['role', function($attribute, $params, $validator) {
                $role = $this->$attribute;
                $roles = ($this->event->categoryDetails['roles'] ?? []);

                if (!array_key_exists($role, $roles)) {
                    $this->addError($attribute, 'Nincs ilyen szerep!');
                } elseif ($roles[$role]['limit'] > 0) { // ha van limit
                    $participatesByRole = ArrayHelper::index($this->event->participates, null, 'role');
                    if (count($participatesByRole[$role] ?? []) >= $roles[$role]['limit']) {
                        $this->addError($attribute, 'Betelt a szerep!');
                    }
                }
            }, 'skipOnEmpty' => true],
            [['event_id', 'user_id'], 'unique', 'targetAttribute' => ['event_id', 'user_id']],
            [['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => Event::className(), 'targetAttribute' => ['event_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Yii::$app->user->identityClass, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Event::className(), ['id' => 'event_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Yii::$app->user->identityClass, ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDays()
    {
        return $this->hasMany(EventParticipateDays::className(), ['event_participate_id' => 'id']);
    }
}
