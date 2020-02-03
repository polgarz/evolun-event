<?php

namespace evolun\event\modules\memo\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "event_memo".
 *
 * @property int $id
 * @property int|null $event_id
 * @property string|null $content
 * @property string|null $created_at
 * @property int|null $created_by
 *
 * @property Event $event
 * @property User $createdBy
 * @property User $updatedBy
 */
class EventMemo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public function behaviors() : array
    {
        return [
            ['class' => BlameableBehavior::className(), 'updatedByAttribute' => false],
            ['class' => TimestampBehavior::className(), 'value' => new \yii\db\Expression('NOW()'), 'updatedAtAttribute' => false],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event_memo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_id', 'created_by'], 'integer'],
            [['content'], 'string'],
            [['created_at'], 'safe'],
            [['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => Yii::$app->controller->module->eventModelClass, 'targetAttribute' => ['event_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Yii::$app->user->identityClass, 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Yii::$app->controller->module->eventModelClass, ['id' => 'event_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(Yii::$app->user->identityClass, ['id' => 'created_by']);
    }

    public function fields()
    {
        return [
            'id',
            'content',
            'created_at' => function($model) {
                return Yii::$app->formatter->asDateTime($model->created_at);
            },
            'created_by' => function($model) {
                return $model->createdBy->name ?? '';
            }
        ];
    }
}
