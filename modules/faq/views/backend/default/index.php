<?php

use yii\grid\CheckboxColumn;
use yii\jui\DatePicker;
use yii\grid\ActionColumn;
use modules\themes\admin\widgets\Box;
use modules\faq\Module;
use modules\themes\admin\widgets\GridView;
use modules\faq\models\Faq;
use \yii\helpers\Html;
use modules\themes\Module as ThemeModule;

$this->title = Module::t('faq', 'BACKEND_INDEX_TITLE');
$this->params['subtitle'] = Module::t('faq', 'BACKEND_INDEX_SUBTITLE');
$this->params['breadcrumbs'] = [
    $this->title
];

$gridId = 'faq-grid';

$statusArray = Faq::getStatusArray();

$gridConfig = [
    'id' => $gridId,
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'pjax'=>true,
    'pjaxSettings'=>[
        'neverTimeout'=>true,
        'options'=>[
            'id'=>'pjax-faq',
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
            'attribute' => 'request',
            'format' => 'html',
            'value' => function ($model) {
                return Html::a(
                    $model['request'],
                    ['update', 'id' => $model['id']]
                );
            }
        ],
        'response',
        [
            'attribute' => 'status',
            'format' => 'html',
            'value' => function ($model) use($statusArray){
                if ($model->status) {
                    $class = 'label-success';
                    $status = $statusArray[Faq::STATUS_ACTIVE];
                } else {
                    $class = 'label-danger';
                    $status = $statusArray[Faq::STATUS_INACTIVE];
                }
                return '<span class="label ' . $class . '">' . $status . '</span>';
            },
            'filter' => \yii\helpers\Html::activeDropDownList(
                $searchModel,
                'status',
                $statusArray,
                ['class' => 'form-control', 'prompt' => Module::t('faq', 'BACKEND_FAQ_STATUS')]
            )
        ],
    ]
];

$boxButtons = $actions = [];
$showActions = false;

$boxButtons[] = '{create}';

$actions[] = '{update}';
$showActions = $showActions || true;

$gridButtons = [];
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