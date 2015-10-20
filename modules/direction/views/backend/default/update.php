<?php

/**
 * Direction update view.
 *
 * @var yii\base\View $this View
 * @var modules\direction\models\Direction $model Model
 * @var \modules\themes\admin\widgets\Box $box Box widget instance
 * @var array $statusArray Statuses array
 */

use modules\themes\admin\widgets\Box;
use modules\direction\Module;
use modules\direction\widgets\DirectionChildrenWidget;
use modules\direction\widgets\DirectionLangWidget;

$this->title = Module::t('direction', 'BACKEND_UPDATE_TITLE');
$this->params['subtitle'] = Module::t('direction', 'BACKEND_UPDATE_SUBTITLE');
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        'url' => ['index'],
    ],
    $this->params['subtitle']
];
$boxButtons = ['{cancel}'];

if (Yii::$app->user->can('BCreateDirection')) {
    $boxButtons[] = '{create}';
}
if (Yii::$app->user->can('BDeleteDirection')) {
    $boxButtons[] = '{delete}';
}
$boxButtons = !empty($boxButtons) ? implode(' ', $boxButtons) : null; ?>
<div class="row">
    <div class="col-sm-12">
        <?php $box = Box::begin(
            [
                'title' => $this->params['subtitle'],
                'renderBody' => false,
                'options' => [
                    'class' => 'box-success'
                ],
                'bodyOptions' => [
                    'class' => 'table-responsive'
                ],
                'buttonsTemplate' => $boxButtons
            ]
        );
        echo $this->render(
            '_form',
            [
                'model' => $model,
                'box' => $box
            ]
        );
        Box::end(); ?>
    </div>
</div>

<div>
    <?= DirectionChildrenWidget::widget();?>
</div>
<div>
    <?= DirectionLangWidget::widget();?>
</div>
