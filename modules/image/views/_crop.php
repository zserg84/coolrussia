<?php
use \yii\helpers\Html;

/**
 * @var \yii\web\View $this
 */

echo Html::tag('span', 'Выберите область отображения');
echo Html::fileInput('img', null, ['class'=>'js_input_event_change_cover']);
?>
<div style="width:538px;height:300px;border:1px #ccc solid;">
    <div class="image_crop_box js_image_crop_box" style="position:relative;width:538px;height:300px;">
        <div class="image_crop_box_cropper _cropper" style="border:1px dashed #ccc;display:none;height:200px;position:relative;width:200px;"></div>
    </div>
</div>
<br style="clear:both;" />
<div class="select-userpic-text greyFont smallFont">
    <button type="button" disabled="disabled" class="btn btn-primary _editAvatarApply">Применить</button>
    <button type="button" class="btn btn-default" onclick="$(this).closest('.modal').modal('hide');">Отмена</button>
</div>