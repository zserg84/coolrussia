<?
use yii\helpers\Html;
?>
<table>
    <tr>
        <?foreach($weekdays as $k=>$day):?>
            <th>
                <?=Html::checkbox('weekday[]', false, ['id'=>'weekday_'.$k, 'class'=>'weekday_checkbox', 'data-day'=>$k, 'value'=>$k])?>
                <?=Html::label($day, 'weekday_'.$k)?>
            </th>
        <?endforeach?>
    </tr>
    <tr>
        <?foreach($weekdays as $k=>$day):?>
            <td class="weekday_content day_<?=$k?>" style="visibility: hidden">
                <div class="date_block" data-date="<?=$k?>">
                    <span class="item_block">
                        <input type="text" name="dateCalendar[<?=$k?>][]">
                        <span class="remove" style="display:none">X</span>
                    </span>
                    <div class="add add_timetable">+</div>
                </div>
            </td>
        <?endforeach?>
    </tr>
</table>