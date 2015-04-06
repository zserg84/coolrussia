<?php

use modules\geo\Module;
use modules\geo\models\GeoCountry;
use modules\geo\models\GeoRegion;
use modules\geo\models\GeoCity;
use yii\grid\CheckboxColumn;
use yii\grid\ActionColumn;
use modules\themes\admin\widgets\Box;
use modules\themes\admin\widgets\GridView;
use modules\themes\Module as ThemeModule;
use \yii\helpers\Html;
use yii\helpers\ArrayHelper;

$this->title = Module::t('geo-city', 'BACKEND_INDEX_TITLE');
$this->params['subtitle'] = Module::t('geo-city', 'BACKEND_INDEX_SUBTITLE');
$this->params['breadcrumbs'] = [
    $this->title
];

$confirmArray = GeoCity::getConfirmArray();

$gridId = 'geo-grid';

if($searchModel->country_id)
    $regions = GeoRegion::find()->where(['country_id'=>$searchModel->country_id])->all();
else
    $regions = GeoRegion::find()->all();

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
            'attribute' => 'country_id',
            'value' => function ($model, $index, $dataColumn) {
                return $model->country->name_ru;
            },
            'filter' => ArrayHelper::map(GeoCountry::find()->all(), 'id', 'name_ru'),
        ],
        [
            'attribute' => 'region_id',
            'value' => function ($model, $index, $dataColumn) {
                return $model->region->name_ru;
            },
            'filter' => ArrayHelper::map($regions, 'id', 'name_ru'),
        ],
        [
            'attribute' => 'name_ru',
            'format' => 'html',
            'value' => function ($model) {
                return Html::a(
                    $model['name_ru'],
                    ['update', 'id' => $model['id']]
                );
            },
        ],
        'name_en',
        'sort',
        [
            'attribute' => 'confirm',
            'format' => 'html',
            'value' => function ($model) use($confirmArray){
                if ($model->confirm) {
                    $class = 'label-success';
                    $confirm = $confirmArray[GeoCity::CONFIRM_ACTIVE];
                } else {
                    $class = 'label-danger';
                    $confirm = $confirmArray[GeoCity::CONFIRM_INACTIVE];
                }
                return '<span class="label ' . $class . '">' . $confirm . '</span>';
            },
            'filter' => Html::activeDropDownList(
                $searchModel,
                'confirm',
                $confirmArray,
                ['class' => 'form-control', 'prompt' => Module::t('geo-city', 'BACKEND_CONFIRM_STATUS')]
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