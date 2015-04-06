<?
if($value){
    $amount = $value['amount'];
    $people_min = $value['people_min'];
    $people_max = $value['people_max'];
}
else{
    $amount = 1;
    $people_min = '';
    $people_max = '';
}
?>
<div class="item_block">
    <span>
        <input type="text" name="amount[]" value="<?=$amount?>"/> Р
    </span>
    <span>
        от <input type="text" name="people_min[]" value="<?=$people_min?>"/> до <input type="text" name="people_max[]" value="<?=$people_max?>" /> человек
    </span>
    <span class="remove_cost" style="visibility:hidden">Удалить запись</span>
</div>
<div class="add add_cost">+</div>