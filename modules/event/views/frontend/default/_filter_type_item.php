<?
use modules\event\models\EventCategory;
use yii\helpers\ArrayHelper;
?>
<div class="accordion">
    <h3><?=$type->name?></h3>
    <div>
        <?
        $typeId = $type->id;
        /*$categories = EventCategory::find()->innerJoinWith([
            'eventCategoryTypes' =>function($query) use($typeId){
                $query->where([
                    'type_id' => $typeId,
                ]);
            }
        ])->all();*/
        $eventCategories = EventCategory::find()->eventCategoriesByType([$typeId])->all();
        $eventCategories = ArrayHelper::map($eventCategories, 'id', 'name');
        echo $form->field($model, 'event_categories')->checkboxList($eventCategories, ['name'=>'event_categories'])->label(false);
        ?>
    </div>
</div>