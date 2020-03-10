<?php

namespace evolun\event\models;

use Yii;
use yii\helpers\ArrayHelper;
use evolun\event\Module;

/**
 * This is the model class for table "event".
 *
 * @property int $id
 * @property string $title
 * @property string $start
 * @property string|null $end
 * @property string|null $description
 * @property string|null $place
 * @property string $category
 * @property int|null $organizer_user_id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property User $updatedBy
 * @property User $createdBy
 */
class Event extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'start', 'category', 'place'], 'required'],
            [['start', 'end', 'created_at', 'updated_at'], 'safe'],
            [['description'], 'string'],
            [['category'], 'in', 'range' => array_keys(Yii::$app->controller->module->categories)],
            [['organizer_user_id', 'created_by', 'updated_by'], 'integer'],
            [['title', 'place', 'category'], 'string', 'max' => 255],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => Yii::$app->user->identityClass, 'targetAttribute' => ['updated_by' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Yii::$app->user->identityClass, 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'title' => Yii::t('event', 'Title'),
            'start' => Yii::t('event', 'Start'),
            'end' => Yii::t('event', 'End'),
            'description' => Yii::t('event', 'Description'),
            'place' => Yii::t('event', 'Place'),
            'category' => Yii::t('event', 'Category'),
            'organizer_user_id' => Yii::t('event', 'Organizer'),
            'created_at' => Yii::t('event', 'Created at'),
            'updated_at' => Yii::t('event', 'Updated at'),
            'created_by' => Yii::t('event', 'Created by'),
            'updated_by' => Yii::t('event', 'Updated by'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(Yii::$app->user->identityClass, ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(Yii::$app->user->identityClass, ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganizer()
    {
        return $this->hasOne(Yii::$app->user->identityClass, ['id' => 'organizer_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParticipates()
    {
        return $this->hasMany(EventParticipate::className(), ['event_id' => 'id']);
    }

    /**
     * Visszaaadja a jelentkezoket szerep alapjan indexelve
     * @return array
     */
    public function getParticipatesByRole() : array
    {
        return ArrayHelper::index($this->participates, null, 'role');
    }

    /**
     * Visszaadja a kategória részleteit
     * @return array
     */
    public function getCategoryDetails() : array
    {
        $module = Yii::$app->getModule('event');

        return $module->categories[$this->category] ?? [];
    }

    /**
     * Visszaadja a napokat, amikor az esemény tart
     * @return array DateTime objektumok
     */
    public function getDays() : array
    {
        $days = [];

        $begin = new \DateTime($this->start);
        if ($this->start) {
            $end = new \DateTime($this->end);
        } else {
            $end = clone $begin;
        }
        $end->setTime(23, 59);
        $interval = new \DateInterval('P1D');
        $period = new \DatePeriod($begin, $interval, $end);

        foreach ($period as $date) {
            $days[] = $date;
        }

        return $days;
    }

    /**
     * Visszaadja a dátum összegzést
     * @return string
     */
    public function getDateSummary() : string
    {
        $dates = [];

        // ha az ev nem ugyan az, mint a mostani ev, csak akkor mutatjuk az evet
        if (date('Y', strtotime($this->start)) != date('Y')) {
            $dates[] = Yii::$app->formatter->asDateTime($this->start, 'yyyy. MMM dd. (EE) HH:mm');
        } else {
            $dates[] = Yii::$app->formatter->asDateTime($this->start, 'MMM dd. (EE) HH:mm');
        }

        if ($this->end) {
            if (date('Y', strtotime($this->end)) != date('Y', strtotime($this->start))) {
                $dates[] = Yii::$app->formatter->asDateTime($this->end, 'yyyy. MMM dd. (EE) HH:mm');
            } elseif (date('Ymd', strtotime($this->start)) != date('Ymd', strtotime($this->end))) {
                $dates[] = Yii::$app->formatter->asDateTime($this->end, 'MMM dd. (EE) HH:mm');
            } else {
                $dates[] = Yii::$app->formatter->asDateTime($this->end, 'HH:mm');
            }
        }

        return implode(' - ', $dates);
    }
}
