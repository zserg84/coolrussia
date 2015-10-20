<?php

use common\components\Modal;

Modal::begin([
    'id' => 'settings-popup',
    'header' => '<p class="title">Настройки</p>',
    'footer' => '
        <button id="settings_submit">Сохранить</button>
        <button class="cancelBtn">Отмена</button>
    ',
    'clientOptions' => false,
]);
echo $this->render('_form', [
    'model' => $model,
    'setting' => $setting,
]);

$this->registerJs('
    $(document).on("click", "#settings_submit", function(){
        var form = $(this).closest(".modal-content").find(".modal-body form");
        form.submit();
    });
');
Modal::end();