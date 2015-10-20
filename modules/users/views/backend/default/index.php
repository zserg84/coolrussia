<?php

/**
 * Users list view.
 *
 * @var \yii\base\View $this View
 * @var \yii\data\ActiveDataProvider $dataProvider Data provider
 * @var \modules\users\models\backend\UserSearch $searchModel Search model
 * @var array $roleArray Roles array
 * @var array $statusArray Statuses array
 */

use modules\themes\admin\widgets\Box;
use modules\themes\admin\widgets\GridView;
use modules\users\Module;
use yii\grid\ActionColumn;
use yii\grid\CheckboxColumn;
use yii\helpers\Html;
use yii\jui\DatePicker;

$this->title = Module::t('users', 'BACKEND_INDEX_TITLE');
$this->params['subtitle'] = Module::t('users', 'BACKEND_INDEX_SUBTITLE');
$this->params['breadcrumbs'] = [
    $this->title
];
$gridId = 'users-grid';
$gridConfig = [
    'id' => $gridId,
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'class' => CheckboxColumn::classname()
        ],
        'id',
        [
            'attribute' => 'name',
            'format' => 'html',
            'value' => function ($model) {
                /** @var $model \modules\users\models\backend\User **/
                return Html::a($model->getAttribute('name'), ['update', 'id' => $model['id']], ['data-pjax' => 0]);
            }
        ],
        'email',
        [
            'attribute' => 'status_id',
            'format' => 'raw',
            'value' => function ($model) {
                $btn = '';
                if ($model->status_id === $model::STATUS_ACTIVE) {
                    $class = 'label-success';
                } elseif ($model->status_id === $model::STATUS_INACTIVE) {
                    $class = 'label-warning';
                    $btn = ' <span class="btn btn-xs btn-primary js_send_activate_mail" data-id="'.$model->id.'" data-state="0" title="Отправить письмо повторно"><i class="fa fa-mail-forward"></i></span>';
                    /**/?><span class="btn-info" ></span><?/**/
                } else {
                    $class = 'label-danger';
                }

                return '<span class="label '.$class.'">'.$model->status.'</span>'.$btn;
            },
            'filter' => Html::activeDropDownList(
                $searchModel,
                'status_id',
                $statusArray,
                ['class' => 'form-control', 'prompt' => Module::t('users', 'BACKEND_PROMPT_STATUS')]
            )
        ],
        [
            'attribute' => 'role',
            'filter' => Html::activeDropDownList(
                $searchModel,
                'role',
                $roleArray,
                ['class' => 'form-control', 'prompt' => Module::t('users', 'BACKEND_PROMPT_ROLE')]
            )
        ],
        [
            'attribute' => 'created_at',
            'format' => 'date',
            'filter' => DatePicker::widget(
                [
                    'model' => $searchModel,
                    'attribute' => 'created_at',
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
            'attribute' => 'updated_at',
            'format' => 'date',
            'filter' => DatePicker::widget(
                [
                    'model' => $searchModel,
                    'attribute' => 'updated_at',
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

/*if (Yii::$app->user->can('BCreateUsers')) {
    $boxButtons[] = '{create}';
}*/
if (Yii::$app->user->can('BUpdateUsers')) {
    $actions[] = '{update}';
    $showActions = $showActions || true;
}
/*if (Yii::$app->user->can('BDeleteUsers')) {
    $boxButtons[] = '{batch-delete}';
    $actions[] = '{delete}';
    $showActions = $showActions || true;
}*/

if ($showActions === true) {
    $gridConfig['columns'][] = [
        'class' => ActionColumn::className(),
        'template' => implode(' ', $actions)
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

<?
$urlActivate = \yii\helpers\Url::toRoute('send-activate-mail');
$this->registerJs("
$('.js_send_activate_mail').click(function(){
    var \$btn = $(this),
    id = parseInt(\$btn.data('id')),
    state = parseInt(\$btn.data('state'));
    if (state != 1) {
        \$btn.data('state', 1);
        \$btn.css('opacity', '0.5');
        $.post('{$urlActivate}', {id:id}, function(data) {
            \$btn.css('opacity', '1');
            if (data == 'ok') {
                \$btn.removeClass('btn-primary');
                \$btn.addClass('btn-default');
                \$btn.title('Отправлено');
            } else {
                \$btn.data('state', 0);
            }
        });
    }
});
");