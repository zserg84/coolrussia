<?php

/**
 * Geo update view.
 *
 * @var yii\base\View $this View
 * @var modules\faq\models\backend\Geo $model Model
 * @var \modules\themes\admin\widgets\Box $box Box widget instance
 * @var array $statusArray Statuses array
 */

use modules\themes\admin\widgets\Box;
use modules\geo\Module;

$this->title = Module::t('geo-region', 'BACKEND_UPDATE_TITLE');
$this->params['subtitle'] = Module::t('geo-region', 'BACKEND_UPDATE_SUBTITLE');
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        'url' => ['index'],
    ],
    $this->params['subtitle']
];
$boxButtons = ['{cancel}'];
$boxButtons[] = '{create}';
$boxButtons[] = '{delete}';

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