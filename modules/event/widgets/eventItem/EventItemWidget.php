<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 04.03.15
 * Time: 16:41
 */

namespace modules\event\widgets\eventItem;

use modules\event\models\EventCost;
use modules\event\models\EventDate;
use modules\event\models\EventTimeRepeat;
use modules\event\Module;
use modules\lang\models\Lang;
use yii\base\Exception;
use yii\bootstrap\Widget;

class EventItemWidget extends Widget
{
    public $model;

    public $editUrl;

    public function init(){
        if(!$this->model){
            throw new Exception(Module::t('event', 'Model is not set'));
        }
    }

    public function run(){
        $model = $this->model;

        $languages = Lang::find()->innerJoinWith([
            'eventLangs' => function ($query) use($model){
                $query->where(['event_id'=>$model->id]);
            }
        ])->all();

        //  ----
        $actualEventDates = EventDate::find()->actualEventDates($model->id)->all();
        $dateArray = [];
        $closestEventDate = null;

        if($actualEventDates){
            foreach($actualEventDates as $date){
                $dateArray[] = \Yii::t('app', '{0, date, d LLLL}', $date->date_start);
                $closestEventDate = $closestEventDate && $closestEventDate < $date->date_start ? $closestEventDate : $date->date_start;
            }
            $timeToEvent = EventDate::getDatesInterval($closestEventDate);
        }
        else{
            $timeToEvent = EventTimeRepeat::getTimeToEvent($model);
        }
        $datesList = implode(',', $dateArray);

        //  ---
        $eventCosts = EventCost::find()->where([
            'event_id'=>$model->id,
        ])->all();
        $costArr = [];
        foreach($eventCosts as $cost){
            $costArr[] = $cost->amount;
        }

        return $this->render('item',[
            'model' => $model,              //  Модель Event
            'languages' => $languages,      //  Список языков для события
            'editUrl' => $this->editUrl,    //  Url для перехода при щелчке на событии
            'timeToEvent' => $timeToEvent,  //  Время, оставшееся до начала события
            'datesList' => $datesList,      //  Список дат начала события
            'costArr'  =>  $costArr,        //  Список цен на событие
        ]);
    }
} 