<?php

use \yii\helpers\Html;
use \yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var array $pageParams
 * @var string $page
 */

$event_id = $pageParams['event_id'];

$wizardArr = [
    '_step1'=>'Параметры',
    '_step2'=>'Описание',
    '_step3'=>'Фотографии',
];
?>
<div class="form-control">
    <?
    $i = 1;
    foreach ($wizardArr as $k=>$label) {
        if ($k == $page) {
            ?><b><?=$label?></b><?
        } elseif ($event_id) {
            $link = Url::toRoute(['/event/default/edit', 'id'=>$event_id, 'step'=>$i]);
            echo Html::a($label, $link, ['']);
        } else {
            ?><span><?=$label?></span><?
        }
        if ($i < (count($wizardArr))) {
            echo ' &gt; ';
        }
        $i++;
    }
    ?>
</div>
<br />