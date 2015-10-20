<?php
use common\components\Modal;
use modules\themes\Module as ThemeModule;

$createFlag = isset($createFlag) ? $createFlag : false;
$modalId = $createFlag ? 'order-create-modal' : 'order-update-modal';

Modal::begin([
    'id' => $modalId,
    'header' => '<p class="title">'.ThemeModule::t('FORM_ORDER', 'ORDER_FORM_NAME').'</p>',
    'clientOptions' => false,
    'options' => [
        'data-comment' => 'modal',
    ],
]);

echo $this->render('_create_form', [
    'model' => $model,
]);

Modal::end();