<?php

/**
 * Activation email view.
 *
 * @var \yii\web\View $this View
 * @var \modules\users\models\frontend\User $model Model
 */

use yii\helpers\Html;
use yii\helpers\Url;
use \modules\users\models\frontend\User;

$url = Url::toRoute(['/users/guest/activation', 'token' => $model['token']], true); ?>
<p>Здравствуйте <?= Html::encode($model['name']) ?>!</p>
<?
if ($model->status_id === User::STATUS_INACTIVE) {
    ?>
    <p>Перейдите по ссылке ниже чтобы подтвердить свой электронный адрес и активировать свой аккаунт:</p>
    <p><?= Html::a(Html::encode($url), $url) ?></p>
    <?
}
if ($password = $model->getSrcPassword()) {
    ?><p>Ваш пароль: <b><?=$password?></b></p><?
}