<?
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use modules\themes\Module as ThemeModule;

$form = ActiveForm::begin([
    'enableAjaxValidation' => false,
    'validateOnSubmit' => false,
    'validateOnChange' => false,
    'validateOnBlur' => false,
    'options' => [
        'id' => 'quick_login_form',
        'data-pjax' => false,
    ],
    'action' => Url::toRoute(['/users/guest/quicklogin/']),
]);
?>
    <div class="form-group">
        <div class="col-sm-6">
            <?
            $field = $form->field($model, 'email')->textInput(['class'=>'form-control col-sm-6"','placeholder'=>ThemeModule::t('Login form for ALL pages', 'LOGIN_LOGIN_FORM_ALL_PAGES')])->label(false)->error(false);
            $field->options = ['class' => null];
            echo $field;
            ?>
        </div>
        <div class="col-sm-6">
            <?=Html::submitButton(ThemeModule::t('Login form for ALL pages', 'ENTRANCE_BUTTON_LOGIN_FOR_ALL_PAGES'))?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-6">
            <?
            $field = $form->field($model, 'password')->passwordInput(['class'=>'form-control','placeholder'=>ThemeModule::t('Login form for ALL pages', 'PASSWORD_LOGIN_FORM_ALL_PAGES')])->label(false)->error(false);
            $field->options = ['class' => null];
            echo $field;
            ?>
        </div>
        <div class="links col-sm-6">
            <p><a href="<?=Url::toRoute(['/recovery/'])?>" class="recovery"><?=ThemeModule::t('Login form for ALL pages', 'FORGOT_PASSWORD_BUTTON_LOGIN_FOR_ALL_PAGES')?></a></p>
            <p>
                <?=Html::a(ThemeModule::t('Login form for ALL pages', 'REGISTRATION_BUTTON_LOGIN_FOR_ALL_PAGES'), Url::toRoute(['/signup/']))?>
            </p>
        </div>
    </div>
<?ActiveForm::end();

$js = <<<JS
jQuery('#quick_login_form').on('submit', function(){
    var form = jQuery(this);
    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        success: function (response, status, xhr) {console.log(123);
            response = xhr.responseJSON;
            response = jQuery.parseJSON(response);
            if(response.output == 'error'){
                $('#login-modal').modal();
            }
        }
    });
    return false;
});
JS;
$this->registerJs($js);

echo \modules\users\widgets\quicklogin\ModalLoginWidget::widget();

echo \modules\users\widgets\quicklogin\RecoveryWidget::widget();

?>