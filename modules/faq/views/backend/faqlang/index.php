<?
use modules\themes\admin\widgets\Box;
use yii\grid\ActionColumn;
use yii\grid\CheckboxColumn;
use kartik\grid\GridView;
use modules\faq\Module;
use modules\themes\Module as ThemeModule;

?>
<div>
    <?
    $gridId = 'faq-lang-grid';
    $gridConfig = [
        'id' => $gridId,
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax'=>true,
        'pjaxSettings'=>[
            'neverTimeout'=>true,
            'options'=>[
                'id'=>'pjax-faq-lang',
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
            [
                'attribute' => '_langName',
                'value' => 'lang.name',
            ],
            'request',
            'response',
        ]
    ];

    $boxButtons = $actions = [];
    $showActions = false;
    $buttons = [];

    $boxButtons[] = '{create}';
    $buttons['create'] = [
        'icon' => 'fa-plus',
        'options' => [
            'class' => 'btn-default',
            'title' => ThemeModule::t('themes-admin', 'Box-Create')
        ],
        'url'=>['create', 'faqId'=>$faqId],
    ];

    $actions[] = '{update}';
    $showActions = $showActions || true;

    $gridButtons = [];
    $boxButtons[] = '{batch-delete}';
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

    $boxButtons = !empty($boxButtons) ? implode(' ', $boxButtons) : null;

    ?>
    <div class="row">
        <div class="col-xs-12">
            <?php Box::begin(
                [
                    'title' => Module::t('faqlang', 'BACKEND_INDEX_SUBTITLE'),
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
</div>