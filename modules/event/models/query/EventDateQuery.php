<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 05.03.15
 * Time: 10:57
 */

namespace modules\event\models\query;


use yii\db\ActiveQuery;

class EventDateQuery extends ActiveQuery {

    /*
     * Даты, актуальные для данного события
     * */
    public function actualEventDates($eventId)
    {
        $curTime = time();
        return $this->where('event_id =:event and date_start > :curTime', [
            'event' => $eventId,
            'curTime' => $curTime
        ]);
    }
}