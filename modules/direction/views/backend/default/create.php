<?php

/**
 * Direction create view.
 *
 * @var \yii\base\View $this View
 * @var \modules\direction\models\Direction $model Model
 * @var \vova07\themes\admin\widgets\Box $box Box widget instance
 */

use modules\themes\admin\widgets\Box;
use modules\direction\Module;

$this->title = Module::t('direction', 'BACKEND_CREATE_TITLE');
$this->params['subtitle'] = Module::t('direction', 'BACKEND_CREATE_SUBTITLE');
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        'url' => ['index'],
    ],
    $this->params['subtitle']
]; ?>
<div class="row">
    <div class="col-sm-12">
        <?php $box = Box::begin(
            [
                'title' => $this->params['subtitle'],
                'renderBody' => false,
                'options' => [
                    'class' => 'box-primary'
                ],
                'bodyOptions' => [
                    'class' => 'table-responsive'
                ],
                'buttonsTemplate' => '{cancel}'
            ]
        );
        $disabledDirection = $model->parent_id ? 'disabled' : false;
        echo $this->render(
            '_form',
            [
                'model' => $model,
                'box' => $box,
                'disabledDirection' => $disabledDirection,
            ]
        );
        Box::end(); ?>
    </div>
</div>