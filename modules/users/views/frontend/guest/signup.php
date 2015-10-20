<?php

/**
 * Signup page view.
 *
 * @var \yii\web\View $this View
 * @var \yii\widgets\ActiveForm $form Form
 * @var \modules\users\models\frontend\User $user Model
 * @var \modules\users\models\Profile $profile Profile
 *
 * @var $tmp_avatar string | null
 */

use modules\users\Module;
use \yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\jui\AutoComplete;
use \yii\jui\DatePicker;
use \yii\web\JsExpression;

$this->title = Module::t('users', 'FRONTEND_SIGNUP_TITLE');
$this->params['breadcrumbs'] = [
    $this->title
]; ?>


<?php $form = ActiveForm::begin(
    [
        'enableAjaxValidation' => true,
        'options' => [
            'class' => 'center'
        ]
    ]
); ?>

    <fieldset class="registration-form">
        <?= yii\authclient\widgets\AuthChoice::widget(['baseAuthUrl' => ['auth/index']]) ?>

        <?
        echo $form->field($user, 'name')->textInput(['placeholder' => $user->getAttributeLabel('name')])->label(false);
        echo $form->field($user, 'email')->input('email', ['placeholder' => $user->getAttributeLabel('email')])->label(false);

        if ($user->birthday) {
            echo DatePicker::widget([
                'model' => $user,
                'attribute' => 'birthday',
                'options' => [
                    'class'=>'form-control',
                    'placeholder'=>$user->getAttributeLabel('birthday'),
                ],
            ]);
        }

        if ($tmp_avatar) {
            echo $form->field($user, 'use_avatar')->label(false)->hiddenInput(['id'=>'signup__use_avatar']);
            ?><div class="form-group" id="signup__use_avatar_box" style="width:200px;height:200px;margin:10px auto;position:relative;"><?
            echo Html::tag('span', '&#10006;', ['id'=>'signup__btn_use_avatar', 'title'=>'Не использовать этот аватар', 'style'=>'cursor:pointer;position:absolute;z-index:1;top:3px;right:3px;font-weight:400;background:#fff;padding:0 6px;border-radius:2px;']);
            echo Html::img($tmp_avatar, ['class'=>'tmp_avatar']);
            ?></div><?
        }

        echo Html::tag('span', Module::t('users', 'FRONTEND_SIGNUP_INPUT_SELF_PASSWORD'), ['id'=>'signup__btn_use_password', 'style'=>'cursor:pointer;border-bottom:1px dashed;color:#5762ff;']);
        echo '<br />';
        echo Html::beginTag('div', ['class'=>'signup__use_password_box', 'style'=>'display:none;']);
        echo $form->field($user, 'use_password')->label(false)->hiddenInput(['id'=>'signup__use_password']);
        echo $form->field($user, 'password')->passwordInput(['placeholder' => $user->getAttributeLabel('password')])->label(false);
        echo $form->field($user, 'repassword')->passwordInput(['placeholder' => $user->getAttributeLabel('repassword')])->label(false);
        echo Html::endTag('div');

        $this->registerJs("
            $('#signup__btn_use_avatar').click(function() {
                var \$btn = $(this),
                    \$input = $('#signup__use_avatar'),
                    \$img = $('.tmp_avatar'),
                    val = parseInt(\$input.val());
                if (val) {
                    \$btn.html('&#10004;');
                    \$input.val(0);
                    \$img.css('opacity', '0.5');
                } else {
                    \$btn.html('&#10006;');
                    \$input.val(1);
                    \$img.css('opacity', '1');
                }
            });

            $('#signup__btn_use_password').click(function() {
                var \$input = $('#signup__use_password'),
                    val = parseInt(\$input.val());
                if (val) {
                    \$input.val(0);
                } else {
                    $('#user-password').val('');
                    $('#user-repassword').val('');
                    \$input.val(1);
                }
                $('.signup__use_password_box').toggle();
            });
        ");
        ?>
        <?
        echo Html::submitButton(
            Module::t('users', 'FRONTEND_SIGNUP_SUBMIT'),
            [
                'class' => 'btn btn-success btn-large',
                'style' => 'margin-top:10px;',
            ]
        ) ?>
        <br /><br />
        <?= Html::a(Module::t('users', 'FRONTEND_SIGNUP_RESEND'), ['resend']); ?>
    </fieldset>
<?php ActiveForm::end(); ?>