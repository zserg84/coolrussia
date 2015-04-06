<?php
/**
 * @var \modules\event\models\Event $event
 */

use \yii\helpers\Html;

?>
<h1><?=Html::encode($event->title)?></h1>
<?

if ($user_id = Yii::$app->getUser()->getId()) {
    if ($user_id === $event->user_id) {
        echo Html::a('edit', $event->getEditUrl(), ['class'=>'btn btn-xs btn-primary']);
        echo '<br />';
    }
}

if ($event->city_id) {
    echo $event->city->getName(true, true);
    echo '<br />';
}
echo $event->address;