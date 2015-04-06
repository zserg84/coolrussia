<?php
/**
 * Страница одного пользователя.
 * @var yii\base\View $this
 * @var \modules\users\models\User $user
 */

use yii\helpers\Html;

$this->title = $user->name;
$this->params['breadcrumbs'][] = $this->title;

?>
<h1><?= Html::encode($this->title)?></h1>
<?
if ($user->image_id) {
    echo Html::img($user->image->getSrc());
}
if ($user->city_id) {
    echo '<br />'.$user->city->getName(true, true);
}
if ($user->birthday) {
    $date = new DateTime($user->birthday);
    echo '<br />'.$date->format('d.m.Y');
}