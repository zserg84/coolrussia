<?php

use yii\grid\CheckboxColumn;
use \yii\helpers\Html;
use yii\grid\ActionColumn;
use modules\themes\admin\widgets\Box;
use modules\page\Module;
use \modules\lang\models\Lang;
use modules\themes\admin\widgets\GridView;
use modules\themes\Module as ThemeModule;

$this->title = Module::t('page', 'BACKEND_INDEX_TITLE');
$this->params['subtitle'] = Module::t('page', 'BACKEND_INDEX_SUBTITLE');
$this->params['breadcrumbs'] = [
    $this->title
];

$gridId = 'page-grid';

$gridConfig = [
    'id' => $gridId,
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'pjax'=>true,
    'pjaxSettings'=>[
        'neverTimeout'=>true,
        'options'=>[
            'id'=>'pjax-page',
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
            'attribute' => 'url',
            'format' => 'html',
            'value' => function ($model) {
                return Html::a(
                    $model['url'],
                    ['update', 'id' => $model['id']]
                );
            }
        ],
        'title',
        [
            'attribute' => 'lang_id',
            'format' => 'html',
            'value' => function ($model) {
                return $model->getLangName();
            },
            'filter' => Html::activeDropDownList(
                $searchModel,
                'lang_id',
                Lang::getArr(),
                ['class' => 'form-control']
            )
        ],
        [
            'attribute' => '',
            'label' => 'link',
            'format' => 'raw',
            'value' => function ($model) {
                return \yii\helpers\Html::input('text', 'page_'.$model->id, $model->getLink(), ['class'=>'form-control', 'onclick'=>'this.select()']);
            },
        ]
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