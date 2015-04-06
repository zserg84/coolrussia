<?php

use modules\geo\Module;
use yii\grid\CheckboxColumn;
use yii\grid\ActionColumn;
use modules\themes\admin\widgets\Box;
use modules\themes\admin\widgets\GridView;
use modules\themes\Module as ThemeModule;
use \yii\helpers\Html;

$this->title = Module::t('geo-country', 'BACKEND_INDEX_TITLE');
$this->params['subtitle'] = Module::t('geo-country', 'BACKEND_INDEX_SUBTITLE');
$this->params['breadcrumbs'] = [
    $this->title
];

$gridId = 'geo-grid';

$gridConfig = [
    'id' => $gridId,
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'pjax'=>true,
    'pjaxSettings'=>[
        'neverTimeout'=>true,
        'options'=>[
            'id'=>'pjax-geo',
            'enablePushState' => false,
            'options'=>[
                'class' => 'pjax-wraper'
            ],
        ],
    ],
    'columns' => [
        [
            'class' => CheckboxColumn::classname()
        ],
        'id',
        [
            'attribute' => 'name_ru',
            'format' => 'html',
            'value' => function ($model) {
                return Html::a(
                    $model['name_ru'],
                    ['update', 'id' => $model['id']]
                );
            }
        ],
        'name_en',
        'code',
        'sort',
    ]
];

$boxButtons = $actions = [];
$showActions = false;

$boxButtons[] = '{create}';

$actions[] = '{update}';
$showActions = $showActions || true;

$gridButtons = [];
$boxButtons[] = '{batch-delete}';

$actions[] = '{delete}';
$gridButtons['delete'] = function ($url, $model) {
    return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id],
        [
            'class' => 'grid-action',
            'data' => [
                'confirm' => ThemeModule::t('themes-admin','Are you sure you want to delete this item?'),
                'method' => 'post',
                'pjax' => '0',
            ],
        ]);
};
$showActions = $showActions || true;

if ($showActions === true) {
    $gridConfig['columns'][] = [
        'class' => ActionColumn::className(),
        'template' => implode(' ', $actions),
        'buttons'=>$gridButtons,
    ];
}
$boxButtons = !empty($boxButtons) ? implode(' ', $boxButtons) : null; ?>

<div class="row">
    <div class="col-xs-12">
        <?php Box::begin(
            [
                'title' => $this->params['subtitle'],
                'bodyOptions' => [
                    'class' => 'table-responsive'
                ],
                'buttonsTemplate' => $boxButtons,
                'grid' => $gridId
            ]
        ); ?>
        <?= GridView::widget($gridConfig); ?>
        <?php Box::end(); ?>
    </div>
</div>