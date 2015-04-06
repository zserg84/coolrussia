<?php

/**
 * User form view.
 *
 * @var \yii\web\View $this View
 * @var \yii\widgets\ActiveForm $form Form
 * @var \modules\users\models\backend\User $model Model
 * @var array $roleArray Roles array
 * @var array $statusArray Statuses array
 * @var \modules\themes\admin\widgets\Box $box Box widget instance
 */

use vova07\fileapi\Widget;
use modules\users\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin(); ?>
<?php $box->beginBody(); ?>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($user, 'name') ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($user, 'email') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($user, 'password')->passwordInput() ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($user, 'repassword')->passwordInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?=
            $form->field($user, 'status_id')->dropDownList(
                $statusArray,
                [
                    'prompt' => Module::t('users', 'BACKEND_PROMPT_STATUS')
                ]
            ) ?>
        </div>
        <div class="col-sm-6">
            <?=
            $form->field($user, 'role')->dropDownList(
                $roleArray,
                [
                    'prompt' => Module::t('users', 'BACKEND_PROMPT_ROLE')
                ]
            ) ?>
        </div>
    </div>
<?php $box->endBody(); ?>
<?php $box->beginFooter(); ?>
<?= Html::submitButton(
    $user->isNewRecord ? Module::t('users', 'BACKEND_CREATE_SUBMIT') : Module::t('users', 'BACKEND_UPDATE_SUBMIT'),
    [
        'class' => $user->isNewRecord ? 'btn btn-primary btn-large' : 'btn btn-success btn-large'
    ]
) ?>
<?php $box->endFooter(); ?>
<?php ActiveForm::end(); ?>