<?php

/**
 * Geo create view.
 *
 * @var \yii\base\View $this View
 * @var \modules\faq\models\Geo $model Model
 * @var \vova07\themes\admin\widgets\Box $box Box widget instance
 */

use modules\themes\admin\widgets\Box;
use modules\geo\Module;

$this->title = Module::t('geo-city', 'BACKEND_CREATE_TITLE');
$this->params['subtitle'] = Module::t('geo-city', 'BACKEND_CREATE_SUBTITLE');
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
        echo $this->render(
            '_form',
            [
                'model' => $model,
                'box' => $box,
            ]
        );
        Box::end(); ?>
    </div>
</div>