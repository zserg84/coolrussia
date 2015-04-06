<?php

/**
 * @var yii\base\View $this
 * @var modules\users\models\User $model
 */

use yii\helpers\Html;

?>


<div class="media">
    <div class="pull-left">
        <i class="icon-windows icon-md"></i>
    </div>
    <div class="media-body">
        <h3 class="media-heading"><?= Html::a($model->name, ['view', 'id' => $model->id]) ?></h3>
    </div>
</div>