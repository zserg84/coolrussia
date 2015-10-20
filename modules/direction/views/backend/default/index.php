<?php

use yii\grid\CheckboxColumn;
use yii\jui\DatePicker;
use yii\grid\ActionColumn;
use modules\themes\admin\widgets\Box;
use modules\direction\Module;
use modules\themes\admin\widgets\GridView;
use modules\direction\models\Direction;
use \yii\helpers\Html;
use modules\themes\Module as ThemeModule;

$this->title = Module::t('direction', 'BACKEND_INDEX_TITLE');
$this->params['subtitle'] = Module::t('direction', 'BACKEND_INDEX_SUBTITLE');
$this->params['breadcrumbs'] = [
    $this->title
];
$id = Yii::$app->getRequest()->get('id');
$gridId = 'direction-grid';

$filterModel = $id ? false : $searchModel;

$gridConfig = [
    'id' => $gridId,
    'dataProvider' => $dataProvider,
    'filterModel' => $filterModel,
    'pjax'=>true,
    'pjaxSettings'=>[
        'neverTimeout'=>true,
        'options'=>[
            'id'=>'pjax-direction',
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
        'name',
        [
            'attribute' => 'parentName',
            'value' => function ($model) {
                return $model->getParentName();
            }
        ],
    ]
];

$boxButtons = $actions = [];
$showActions = false;


$boxButtons[] = '{create}';
$buttons['create'] = [
    'url' => ['create', 'parent_id'=>$id],
    'icon' => 'fa-plus',
    'options' => [
        'class' => 'btn-default',
        'title' => ThemeModule::t('themes-admin', 'Box-Create')
    ]
];


$actions[] = '{update}';
$showActions = $showActions || true;

$gridButtons = [];
$boxButtons[] = '{batch-delete}';
//    $actions[] = '{delete}';
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
                'buttons' => $buttons,
                'grid' => $gridId
            ]
        ); ?>
        <?= GridView::widget($gridConfig); ?>
        <?php Box::end(); ?>
    </div>
</div>