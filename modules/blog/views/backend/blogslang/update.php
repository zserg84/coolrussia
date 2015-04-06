<?php

/**
 * Blogslang update view.
 *
 * @var yii\base\View $this View
 * @var modules\blog\models\backend\Blogslang $model Model
 * @var \modules\themes\admin\widgets\Box $box Box widget instance
 * @var array $statusArray Statuses array
 */

use vova07\themes\admin\widgets\Box;
use modules\blog\Module;

$this->title = Module::t('blogslang', 'BACKEND_UPDATE_TITLE');
$this->params['subtitle'] = Module::t('blogslang', 'BACKEND_UPDATE_SUBTITLE');
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        'url' => ['index'],
    ],
    $this->params['subtitle']
];
$boxButtons = ['{cancel}'];

if (Yii::$app->user->can('BCreateBlogsLang')) {
    $boxButtons[] = '{create}';
}
if (Yii::$app->user->can('BDeleteBlogsLang')) {
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
                'langArr' => $langArr,
                'box' => $box
            ]
        );
        Box::end(); ?>
    </div>
</div>