<?
use modules\themes\admin\widgets\Box;
use yii\grid\ActionColumn;
use yii\grid\CheckboxColumn;
use modules\blog\Module;
use kartik\grid\GridView;
use modules\themes\Module as ThemeModule;

?>
<div>
    <?
    $gridId = 'blogs-lang-grid';
    $gridConfig = [
        'id' => $gridId,
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax'=>true,
        'pjaxSettings'=>[
            'neverTimeout'=>true,
            'options'=>[
                'id'=>'pjax-blogs-lang',
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
            'title',
            [
                'attribute' => 'snippet',
                'format' => 'html',
                'value' => function ($model) {
                    return substr(strip_tags($model['snippet']), 0, 35) . '...';
                }
            ],
            [
                'attribute' => 'content',
                'format' => 'html',
                'value' => function ($model) {
                    return substr(strip_tags($model['content']), 0, 35) . '...';
                }
            ],
        ]
    ];

    $boxButtons = $actions = [];
    $showActions = false;
    $buttons = [];
    if (Yii::$app->user->can('BCreateBlogsLang')) {
        $boxButtons[] = '{create}';
        $buttons['create'] = [
            'icon' => 'fa-plus',
            'options' => [
                'class' => 'btn-default',
                'title' => ThemeModule::t('themes-admin', 'Box-Create')
            ],
            'url'=>['create', 'blogId'=>$blogId],
        ];
    }
    if (Yii::$app->user->can('BUpdateBlogsLang')) {
        $actions[] = '{update}';
        $showActions = $showActions || true;
    }

    $gridButtons = [];
    if (Yii::$app->user->can('BDeleteBlogsLang')) {
        $boxButtons[] = '{batch-delete}';
        $buttons['batch-delete'] = [
            'url' => ['batch-delete'],
            'icon' => 'fa-trash-o',
            'options' => [
//                'id' => 'batch-delete',
                'class' => 'btn-default batch-delete',
                'title' => ThemeModule::t('themes-admin', 'Box-Delete-Selected'),
                'data' => [
                    'pjax' => '0',
                ],
            ]
        ];
        $actions[] = '{delete}';
        $gridButtons['delete'] = function ($url, $model) {
            return \yii\helpers\Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id],
                [
                    'class' => 'grid-action',
                    'data' => [
                        'confirm' => ThemeModule::t('themes-admin/widgets/box','Are you sure you want to delete this item?'),
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

    $boxButtons = !empty($boxButtons) ? implode(' ', $boxButtons) : null;
    ?>
    <div class="row">
        <div class="col-xs-12">
            <?php Box::begin(
                [
                    'title' => Module::t('blogslang', 'BACKEND_INDEX_SUBTITLE'),
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