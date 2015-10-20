<?php

/**
 * Recovery password page view.
 *
 * @var \yii\web\View $this View
 * @var \yii\widgets\ActiveForm $form Form
 * @var \modules\users\models\frontend\RecoveryForm $model Model
 */

use modules\themes\Module as ThemeModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\Modal;

Modal::begin([
    'id' => 'recovery-modal',
    'header' => '<p class="title">Изменение пароля</p>',
    'clientOptions' => false,
    'options' => [
        'class' => 'modal-recovery modal-small',
    ],
]);

$this->title = ThemeModule::t('GUEST_INTERFACE', 'FRONTEND_RECOVERY_TITLE');
$this->params['breadcrumbs'] = [
    $this->title
];
$this->params['contentId'] = 'error'; ?>
<?php $form = ActiveForm::begin(
    [
        'options' => [
            'class' => 'center'
        ]
    ]
); ?>
    <div class="row">
        <div class="col-md-10 col-md-offset-2">
            Введите e-mail, указанный при регистрации
        </div>
        <div class="col-md-6 col-md-offset-3">
            <?= $form->field($model, 'email')->textInput(['placeholder' => ThemeModule::t('Login form for ALL pages', 'EMAIL_LOGIN_FORM_ALL_PAGES')])->label(false)->error(false) ?>
        </div>
    </div>
    <div class="row">
        <?=Html::submitButton(ThemeModule::t('GUEST_INTERFACE', 'FRONTEND_RECOVERY_SUBMIT')); ?>
        <?=Html::button('Отмена', ['class'=>'cancelBtn',  'style'=>'float:left; left:15px']); ?>
    </div>
<?php
ActiveForm::end();
Modal::end();