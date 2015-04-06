<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 05.03.15
 * Time: 16:08
 */

use modules\event\widgets\eventItem\EventItemWidget;

echo EventItemWidget::widget([
    'model' => $model,
    'editUrl' => $model->getEditUrl(),
]);