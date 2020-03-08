<?php

namespace evolun\event\modules\comments\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "event_comment".
 *
 * @property int $id
 * @property int $user_id
 * @property int $event_id
 * @property string|null $comment
 * @property string|null $date
 *
 * @property Event $event
 * @property User $user
 */
class EventComment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event_comment';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors() : array
    {
        return [
            ['class' => BlameableBehavior::className(), 'createdByAttribute' => 'user_id', 'updatedByAttribute' => false],
            ['class' => TimestampBehavior::className(), 'value' => new \yii\db\Expression('NOW()'), 'updatedAtAttribute' => false, 'createdAtAttribute' => 'date'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'event_id'], 'required'],
            [['user_id', 'event_id'], 'integer'],
            [['comment'], 'string'],
            [['date'], 'safe'],
            [['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => Yii::$app->controller->module->eventModelClass, 'targetAttribute' => ['event_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Yii::$app->user->identityClass, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'comment' => Yii::t('event/comments', 'Comment'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // emailt kuldunk a resztvevoknek, ha valaki uj kommentet ir egy esemenyhez
        if ($participates = $this->event->participates) {
            foreach ($participates as $participate) {
                // annak a usernek nem kuldunk, aki irta
                if ($participate->user_id == Yii::$app->user->id) {
                    continue;
                }

                Yii::$app->mailer->compose(Yii::$app->controller->module->commentNotificationEmail, ['comment' => $this])
                    ->setFrom(Yii::$app->params['mainEmail'])
                    ->setTo($participate->user->email)
                    ->setSubject(Yii::t('event/comments', 'Someone commented on an event that you are going too'))
                    ->send();
            }
        }
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
    public function getUser()
    {
        return $this->hasOne(Yii::$app->user->identityClass, ['id' => 'user_id']);
    }
}
