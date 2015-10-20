<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 08.05.15
 * Time: 16:36
 */

use common\components\Modal;
use yii\helpers\Html;

Modal::begin([
    'id' => 'modal-alert-'.$type,
    'header' => '<p class="title">Информационное сообщение</p>',
    'options' => [
        'class' => 'modal-small',
    ],
]);
?>
<div>
    <?=$message;?>
</div>
<div class="button-block">
    <?=Html::button('OK', ['class'=>'cancelBtn',  'style'=>'float:left; left:15px']); ?>
</div>
<?Modal::end();

$this->registerJs('
    $("#modal-alert-'.$type.'").modal();
');