<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 07.05.15
 * Time: 14:19
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use common\components\Modal;
use modules\themes\Module as ThemeModule;

$form = ActiveForm::begin([
    'id' => 'modal_login_form',
    'enableAjaxValidation' => true,
    'validateOnChange' => false,
    'validateOnBlur' => false,
    'options' => [
        'data-pjax' => false,
    ],
    'action' => Url::toRoute(['/users/guest/modallogin/']),
]);
?>
    <div class="popup-wrapper">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <?
                $field = $form->field($model, 'email')->textInput(['class'=>'form-control col-sm-6"','placeholder'=>ThemeModule::t('Login form for ALL pages', 'LOGIN_LOGIN_FORM_ALL_PAGES')])->label(false)->error(false);
                $field->options = ['class' => null];
                echo $field;
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 col-md-offset-3">
                <?
                $field = $form->field($model, 'password')->passwordInput(['class'=>'form-control','placeholder'=>ThemeModule::t('Login form for ALL pages', 'PASSWORD_LOGIN_FORM_ALL_PAGES')])->label(false)->error(false);
                $field->options = ['class' => null];
                echo $field;
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 col-md-offset-3">
                <p><a href="<?=Url::toRoute(['/recovery/'])?>" class="recovery"><?=ThemeModule::t('Login form for ALL pages', 'FORGOT_PASSWORD_BUTTON_LOGIN_FOR_ALL_PAGES')?></a></p>
                <p>
                    <?=Html::a(ThemeModule::t('Login form for ALL pages', 'REGISTRATION_BUTTON_LOGIN_FOR_ALL_PAGES'), Url::toRoute(['/signup/']))?>
                </p>
            </div>
        </div>
        <div class="row">
            <?=Html::submitButton(ThemeModule::t('Login form for ALL pages', 'ENTRANCE_BUTTON_LOGIN_FOR_ALL_PAGES')); ?>
            <?=Html::button('Отмена', ['class'=>'cancelBtn',  'style'=>'float:left; left:15px']); ?>
        </div>
    </div>

<?
ActiveForm::end();