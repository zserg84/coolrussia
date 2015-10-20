<?php
/**
 * @var View $this
 * @var SourceMessageSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use yii\data\ActiveDataProvider;
use modules\themes\admin\widgets\GridView;
use modules\themes\admin\widgets\Box;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use kartik\grid\ActionColumn;
use yii\widgets\Pjax;
use modules\themes\Module as ThemeModule;
use modules\translations\models\search\SourceMessageSearch;
use modules\translations\Module;
use modules\translations\models\MessageCategory;


$this->title = Module::t('translations', 'BACKEND_INDEX_TITLE');
$this->params['subtitle'] = Module::t('translations', 'BACKEND_INDEX_SUBTITLE');
$this->params['breadcrumbs'] = [
    $this->title
];

$gridId = 'translations-grid';

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
            'attribute' => 'id',
            'value' => function ($model, $index, $dataColumn) {
                return $model->id;
            },
            'filter' => false
        ],
        [
            'attribute' => 'message',
            'format' => 'raw',
            'value' => function ($model, $index, $widget) {
                return Html::a($model->message, ['update', 'id' => $model->id], ['data' => ['pjax' => 0]]);
            }
        ],
        [
            'attribute' => 'category_id',
            'value' => function ($model, $index, $dataColumn) {
                return $model->category->name;
            },
            'filter' => ArrayHelper::map(MessageCategory::find()->all(), 'id', 'name')
//                'filter' => false
//                'filter' => ArrayHelper::map($searchModel::getCategories(), 'category', 'category')
        ],
//            [
//                'attribute' => 'status',
//                'value' => function ($model, $index, $widget) {
//                        return '';
//                    },
//                'filter' => Html::dropDownList($searchModel->formName() . '[status]', $searchModel->status, $searchModel->getStatus(), [
//                        'class' => 'form-control',
//                        'prompt' => ''
//                    ])
//            ]
    ]
];
$boxButtons = $actions = [];
$showActions = false;

if (Yii::$app->user->can('BCreateTranslation')) {
    $boxButtons[] = '{create}';
}
if (Yii::$app->user->can('BUpdateTranslation')) {
    $actions[] = '{update}';
    $showActions = $showActions || true;
}
$gridButtons = [];
if (Yii::$app->user->can('BDeleteTranslation')) {
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