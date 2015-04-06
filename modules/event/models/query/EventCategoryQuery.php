<?php
/**
 * Created by PhpStorm.
 * scopes для модели площадей Space
 * User: alex
 * Date: 05.02.15
 * Time: 11:14
 */

namespace modules\event\models\query;

use modules\event\models\EventCategory;
use yii\db\ActiveQuery;

class EventCategoryQuery extends ActiveQuery
{

    /*
     * Список категорий по типу(тип может быть массивом или одним значением)
     * */
    public function eventCategoriesByType($type){
        return EventCategory::find()->innerJoinWith([
            'eventCategoryTypes' => function ($query) use($type){
                $query->where(['type_id'=>$type]);
            }
        ]);
    }
}