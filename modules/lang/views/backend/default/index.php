<?php

use yii\grid\CheckboxColumn;
use yii\jui\DatePicker;
use yii\grid\ActionColumn;
use modules\themes\admin\widgets\Box;
use modules\lang\Module;
use modules\themes\admin\widgets\GridView;
use modules\themes\Module as ThemeModule;

$this->title = Module::t('lang', 'BACKEND_INDEX_TITLE');
$this->params['subtitle'] = Module::t('lang', 'BACKEND_INDEX_SUBTITLE');
$this->params['breadcrumbs'] = [
    $this->title
];

$gridId = 'lang-grid';

$gridConfig = [
    'id' => $gridId,
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'pjax'=>true,
    'pjaxSettings'=>[
        'neverTimeout'=>true,
        'options'=>[
            'id'=>'pjax-lang',
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
        'url',
        'name',
        [
            'attribute' => 'date_create',
            'format' => 'date',
            'filter' => DatePicker::widget(
                [
                    'model' => $searchModel,
                    'attribute' => 'date_create',
                    'options' => [
                        'class' => 'form-control'
                    ],
                    'clientOptions' => [
                        'dateFormat' => 'dd.mm.yy',
                    ]
                ]
            )
        ],
        [
            'attribute' => 'date_update',
            'format' => 'date',
            'filter' => DatePicker::widget(
                [
                    'model' => $searchModel,
                    'attribute' => 'date_update',
                    'options' => [
                        'class' => 'form-control'
                    ],
                    'clientOptions' => [
                        'dateFormat' => 'dd.mm.yy',
                    ]
                ]
            )
        ]
    ]
];

$boxButtons = $actions = [];
$showActions = false;

if (Yii::$app->user->can('BCreateLang')) {
    $boxButtons[] = '{create}';
}
if (Yii::$app->user->can('BUpdateLang')) {
    $actions[] = '{update}';
    $showActions = $showActions || true;
}

$gridButtons = [];
if (Yii::$app->user->can('BDeleteLang')) {
    $boxButtons[] = '{batch-delete}';
//    $actions[] = '{delete}';
    $actions[] = '{delete}';
    $gridButtons['delete'] = function ($url, $model) {
        return \yii\helpers\Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id],
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
}

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