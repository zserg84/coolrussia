<?php

/**
 * Recovery confirmation page view.
 *
 * @var \yii\web\View $this View
 * @var \yii\widgets\ActiveForm $form Form
 * @var \modules\users\models\frontend\RecoveryConfirmationForm $model Model
 */

use modules\themes\Module as ThemeModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\Modal;

Modal::begin([
    'id' => 'recovery-confirmation-modal',
    'header' => '<p class="title">Изменение пароля</p>',
    'clientOptions' => false,
    'options' => [
        'class' => 'modal-recovery-confirmation modal-small',
    ],
]);
$this->title = ThemeModule::t('GUEST_INTERFACE', 'FRONTEND_RECOVERY_SUBMIT');
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
        <div class="col-md-8 col-md-offset-2">
            <?= $form->field($model, 'password')->passwordInput(['placeholder' => $model->getAttributeLabel('password')])->label(false)->error(false) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?= $form->field($model, 'repassword')->passwordInput(['placeholder' => $model->getAttributeLabel('repassword')])->label(false)->error(false) ?>
            <?= $form->field($model, 'token', ['template' => "{input}\n{error}"])->hiddenInput() ?>
        </div>
    </div>
    <div class="row">
        <?= Html::submitButton(
            ThemeModule::t('GUEST_INTERFACE', 'FRONTEND_RECOVERY_SUBMIT'),
            [
                'class' => 'btn btn-success pull-right'
            ]
        ) ?>
    </div>

<?php
ActiveForm::end();
Modal::end();

$this->registerJs('
    $("#recovery-confirmation-modal").modal();
');