<?php

namespace modules\event\models\query;

use yii\db\ActiveQuery;
use modules\event\models\Event;

/**
 * Class UserQuery
 * @package modules\users\models\query
 */
class EventQuery extends ActiveQuery {

    /**
     * Select active events.
     */
    public function active()
    {
        $this->andWhere(['status' => Event::STATUS_ACTIVE]);
        return $this;
    }


    /**
     * Select hidden events.
     */
    public function hidden()
    {
        $this->andWhere(['status' => Event::STATUS_HIDDEN]);
        return $this;
    }


    /**
     * Select banned events.
     */
    public function banned()
    {
        $this->andWhere(['status' => Event::STATUS_BANNED]);
        return $this;
    }

    /**
     * Select recommended events.
     */
    public function recommended()
    {
        $this->andWhere(['recommended' => 1]);
        return $this;
    }

    /**
     * Select not recommended events.
     */
    public function notRecommended()
    {
        $this->andWhere('recommended IS NULL OR NOT recommended');
        return $this;
    }

    /**
     * Select not actual events.
     */
    public function actual()
    {
        $curTime = time();
        $actualEventDates = $this->active()->joinWith([
            'eventDates' => function ($query) use($curTime){
                $query->from('event_date eventDates')->andOnCondition('date_start > :curTime', ['curTime'=>$curTime]);
            },
            'eventTimeRepeats'=> function ($query) use($curTime){
                $query->from('event_time_repeat eventTimeRepeats');
            }
        ])->where(
            'eventDates.id is not null or eventTimeRepeats.id is not null'
        );

        return $actualEventDates;
    }
}