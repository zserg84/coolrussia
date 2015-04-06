<?php

namespace modules\event\models;

use modules\event\models\query\EventDateQuery;
use Yii;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "event_date".
 *
 * @property integer $id
 * @property integer $event_id
 * @property integer $date_start
 *
 * @property Event $event
 */
class EventDate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_date';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_id', 'date_start'], 'required'],
            [['event_id', 'date_start'], 'integer'],
            ['date_start', 'validateDateStart']
        ];
    }


    public function validateDateStart() {
        if ($this->isNewRecord and ($this->date_start < time())) {
            $this->addError('date_start', 'Дата и время должны быть позже текущего времени.');
        }
        return true;
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
//            'id' => Yii::t('app', 'ID'),
//            'event_id' => Yii::t('app', 'Event ID'),
//            'date_start' => Yii::t('app', 'Date Start'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return new EventDateQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Event::className(), ['id' => 'event_id']);
    }

    public static function getDatesInterval($date1, $date2 = null){
        $date2 = $date2 ? $date2 : time();
        $date1 = date('Y-m-d H:i:s', $date1);
        $date2 = date('Y-m-d H:i:s', $date2);
        $date1 = new \DateTime($date1);
        $date2 = new \DateTime($date2);
        $interval = $date1->diff($date2);
        $return = [];
        if($interval->y){
            $return['value'] = $interval->y;
            $return['dimension'] = 'year';
        }
        elseif($interval->m){
            $return['value'] = $interval->m;
            $return['dimension'] = 'month';
        }
        elseif($interval->d){
            $return['value'] = $interval->d;
            $return['dimension'] = 'day';
        }
        elseif($interval->h){
            $return['value'] = $interval->h;
            $return['dimension'] = 'hour';
        }
        elseif($interval->i){
            $return['value'] = $interval->i;
            $return['dimension'] = 'minute';
        }
        elseif($interval->s){
            $return['value'] = $interval->s;
            $return['dimension'] = 'secunde';
        }
        return $return;
    }

}
