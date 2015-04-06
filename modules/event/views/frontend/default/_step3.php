<?php
use yii\widgets\ActiveForm;
use modules\event\Module;
use \yii\helpers\Html;
use \yii\web\View;
use \yii\helpers\Url;
use \yii\web\JqueryAsset;

/**
 * @var \yii\web\View $this
 * @var \modules\event\models\form\EventForm3 $model
 * @var integer $event_id
 */

$event = $model->event;

$form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data', 'id'=>'form_event_images']]);

echo Html::hiddenInput('event_id', $event_id, ['id'=>'event_id']);

echo $form->field($model, 'cover')->fileInput(['class'=>'js_input_event_change_cover']);
?>
<style>
.event_cover_preview { width:200px;height:200px;border:1px #ccc solid; }
.image_crop_box canvas, .image_crop_box img { position:absolute; z-index:0; }
.ui-resizable-se { height:12px; width:12px; position:absolute; z-index:1; right:0; bottom:0; cursor:nwse-resize; background:url('data:image/gif;base64,R0lGODlhBwAHAIABAM3Nzf///yH/C1hNUCBEYXRhWE1QPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS41LWMwMjEgNzkuMTU1NzcyLCAyMDE0LzAxLzEzLTE5OjQ0OjAwICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ0MgMjAxNCAoTWFjaW50b3NoKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpDMzEyOERFRjAxRTExMUU0ODg2RkREOUQ0OTkwMTMyMiIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDpDMzEyOERGMDAxRTExMUU0ODg2RkREOUQ0OTkwMTMyMiI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOkMzMTI4REVEMDFFMTExRTQ4ODZGREQ5RDQ5OTAxMzIyIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOkMzMTI4REVFMDFFMTExRTQ4ODZGREQ5RDQ5OTAxMzIyIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+Af/+/fz7+vn49/b19PPy8fDv7u3s6+rp6Ofm5eTj4uHg397d3Nva2djX1tXU09LR0M/OzczLysnIx8bFxMPCwcC/vr28u7q5uLe2tbSzsrGwr66trKuqqainpqWko6KhoJ+enZybmpmYl5aVlJOSkZCPjo2Mi4qJiIeGhYSDgoGAf359fHt6eXh3dnV0c3JxcG9ubWxramloZ2ZlZGNiYWBfXl1cW1pZWFdWVVRTUlFQT05NTEtKSUhHRkVEQ0JBQD8+PTw7Ojk4NzY1NDMyMTAvLi0sKyopKCcmJSQjIiEgHx4dHBsaGRgXFhUUExIREA8ODQwLCgkIBwYFBAMCAQAAIfkEAQAAAQAsAAAAAAcABwAAAgyMD2epyeEQnLHOUAAAOw==') 2px 2px no-repeat; }
</style>
<div class="event_cover_preview js_event_cover_preview" data-mixsize="200" data-errorsize="Выберите фотографию не менее 200×200 пикселей" data-url="<?=Url::toRoute(['upload-cover'])?>" onclick="$('.js_btn_show_event_cover_resize').click();">
    <?
    if ($event->image_id) {
        echo Html::img($event->image->getSrc(), ['width'=>200, 'height'=>200]);
    }
    ?>
</div>

<div id="image_ghost" style="width:1px;height:1px;opacity:0;overflow:hidden;position:relative;"></div>

<?
\yii\bootstrap\Modal::begin([
    'toggleButton' => [
        'label' => '',
        'class' => 'js_btn_show_event_cover_resize hidden'
    ],
    'closeButton' => [
        'label' => '&times;',
        'class' => 'btn btn-danger btn-sm pull-right',
        'style' => 'z-index:1100;position:relative;',
    ],
    'id' => 'popup_event_crop_image',
    'size' => 'modal-lg',
    'clientOptions'=>['id'=>'k123'],
]);
echo $this->render('@modules/image/views/_crop');
\yii\bootstrap\Modal::end();
?>


<hr />

<label><?=Module::t('event', 'EVENT_IMAGES')?></label>
<input type="file" id="btn_event_select_images" />
<div class="event_images">

</div>

<hr />

<?
echo Html::submitButton(Module::t('event', 'FRONTEND_BUTTON_PREVIEW'), ['class' => 'btn btn-default btn-large']);
echo '&nbsp;';
echo Html::submitButton(Module::t('event', 'FRONTEND_BUTTON_PUBLISH'), ['class' => 'btn btn-primary btn-large']);

ActiveForm::end();

$this->registerJsFile('/js/jquery-ui-1.10.4.custom.min.js', ['depends' => JqueryAsset::className()]);
$this->registerJsFile('/js/FileAPI/FileAPI.js', ['depends' => JqueryAsset::className()]);
$this->registerJsFile('/js/event/images.js', ['depends' => JqueryAsset::className()]);