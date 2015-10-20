<?
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use common\components\Modal;
use modules\themes\Module as ThemeModule;

Modal::begin([
    'id' => 'login-modal',
    'header' => '<p class="title">Вход в систему</p>',
    'clientOptions' => false,
    'options' => [
        'class' => 'modal-login modal-small',
    ],
]);
echo $this->render('_modallogin_form', [
    'model' => $model,
]);
Modal::end();