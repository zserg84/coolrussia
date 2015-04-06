<?php

/**
 * Faq update view.
 *
 * @var yii\base\View $this View
 * @var modules\faq\models\backend\Faq $model Model
 * @var \modules\themes\admin\widgets\Box $box Box widget instance
 * @var array $statusArray Statuses array
 */

use modules\themes\admin\widgets\Box;
use modules\faq\Module;

$this->title = Module::t('faq', 'BACKEND_UPDATE_TITLE');
$this->params['subtitle'] = Module::t('faq', 'BACKEND_UPDATE_SUBTITLE');
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        'url' => ['index'],
    ],
    $this->params['subtitle']
];
$boxButtons = ['{cancel}'];

if (Yii::$app->user->can('BCreateLang')) {
    $boxButtons[] = '{create}';
}
if (Yii::$app->user->can('BDeleteLang')) {
    $boxButtons[] = '{delete}';
}
$boxButtons = !empty($boxButtons) ? implode(' ', $boxButtons) : null; ?>
<div class="row">
    <div class="col-sm-12">
        <?php $box = Box::begin(
            [
                'title' => $this->params['subtitle'],
                'renderBody' => false,
                'options' => [
                    'class' => 'box-success'
                ],
                'bodyOptions' => [
                    'class' => 'table-responsive'
                ],
                'buttonsTemplate' => $boxButtons
            ]
        );
        echo $this->render(
            '_form',
            [
                'model' => $model,
                'box' => $box
            ]
        );
        Box::end(); ?>
    </div>
</div>

<div>
    <?
    echo \modules\faq\widgets\FaqLang::widget(
        [
            'faqModel' => $model,
        ]
    );
    ?>
</div>
