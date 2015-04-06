<?php

namespace modules\event\models;

use modules\event\Module;
use modules\lang\models\Lang;
use Yii;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "event_time_repeat".
 *
 * @property integer $id
 * @property integer $event_id
 * @property string $dayweek
 * @property string $time
 *
 * @property Event $event
 */
class EventTimeRepeat extends \yii\db\ActiveRecord
{
    const MONDAY = 'Monday';
    const TUESDAY = 'Tuesday';
    const WEDNESDAY= 'Wednesday';
    const THURSDAY = 'Thursday';
    const FRIDAY = 'Friday';
    const SATURDAY = 'Saturday';
    const SUNDAY = 'Sunday';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_time_repeat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_id', 'time'], 'required'],
            [['event_id'], 'integer'],
            [['dayweek'], 'string'],
            [['time'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
//            'id' => Yii::t('app', 'ID'),
//            'event_id' => Yii::t('app', 'Event ID'),
//            'dayweek' => Yii::t('app', 'Dayweek'),
//            'time' => Yii::t('app', 'Time'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Event::className(), ['id' => 'event_id']);
    }

    public static function getWeekDays(){
        return [
            self::MONDAY => Module::t('event', 'MONDAY'),
            self::TUESDAY => Module::t('event', 'TUESDAY'),
            self::WEDNESDAY => Module::t('event', 'WEDNESDAY'),
            self::THURSDAY => Module::t('event', 'THURSDAY'),
            self::FRIDAY => Module::t('event', 'FRIDAY'),
            self::SATURDAY => Module::t('event', 'SATURDAY'),
            self::SUNDAY=> Module::t('event', 'SUNDAY'),
        ];
    }

    public static function getWeekDayIndex($weekday){
        $weekday = trim($weekday);
        foreach(self::weekFormat() as $key=>$weekdayValue){
            if(strtoupper($weekday) == strtoupper($weekdayValue))
                return ++$key;
        }
    }

    public static function weekFormat(){
        if(Lang::getCurrent()->url == 'ru'){
            $weekArray = [
                self::MONDAY,
                self::TUESDAY,
                self::WEDNESDAY,
                self::THURSDAY,
                self::FRIDAY,
                self::SATURDAY,
                self::SUNDAY,
            ];
        }
        else{
            $weekArray = [
                self::SUNDAY,
                self::MONDAY,
                self::TUESDAY,
                self::WEDNESDAY,
                self::THURSDAY,
                self::FRIDAY,
                self::SATURDAY,
            ];
        }
        return $weekArray;
    }

    /*
     * Ищет ближайший к текущему день недели.
     * и возвращает время до этого дня
     * */
    public static function getTimeToEvent($event){
        $curWeekday = \Yii::t('app', '{0, date, cccc}', time());
        $curWeekday = self::getWeekDayIndex($curWeekday);

        $curDate = date('Y-m-d H:i:s');
        $curDate = new \DateTime($curDate);

        $dates = $event->eventTimeRepeats;
        $weekDates = [];
        foreach($dates as $dateModel){
            $dateWeekIndex = EventTimeRepeat::getWeekDayIndex($dateModel->dayweek);
            $diff = $dateWeekIndex - $curWeekday;
            if($diff < 0){
                $diff = 7 + $diff;
            }
            $date = clone $curDate;
            $date = $date->add(new \DateInterval('P'.$diff.'D'));
            $date = $date->format('Y-m-d');
            $date = $date . ' ' . $dateModel->time;
            $date = strtotime($date);
            $weekDates[] = $date;
        }

        $closestEventDate = min($weekDates);
        $timeToEvent = EventDate::getDatesInterval($closestEventDate);
        return $timeToEvent;
    }
}
