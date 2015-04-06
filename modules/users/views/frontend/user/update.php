<?php

/**
 * Update profile page view.
 *
 * @var \yii\web\View $this View
 * @var \yii\widgets\ActiveForm $form Form
 * @var \modules\users\models\frontend\User $user Model
 */

use vova07\fileapi\Widget;
use modules\users\Module;
use \yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\jui\AutoComplete;
use \yii\web\JsExpression;

$this->title = Module::t('users', 'FRONTEND_UPDATE_TITLE');
$this->params['breadcrumbs'] = [
    Module::t('users', 'FRONTEND_SETTINGS_LABEL'),
    $this->title
];
$this->params['contentId'] = 'error'; ?>
<?php $form = ActiveForm::begin(
    [
        'options' => [
            'class' => 'center'
        ]
    ]
); ?>
    <fieldset class="registration-form">
        <?= $form->field($user, 'name')->textInput(['placeholder' => $user->getAttributeLabel('name')])->label(
            false
        ) ?>

        <?/*=
        $form->field($user, 'avatar_url')->widget(
            Widget::className(),
            [
                'settings' => [
                    'url' => ['fileapi-upload']
                ],
                'crop' => true,
                'cropResizeWidth' => 100,
                'cropResizeHeight' => 100
            ]
        )->label(false) */?>
        <?= Html::submitButton(
            Module::t('users', 'FRONTEND_UPDATE_SUBMIT'),
            [
                'class' => 'btn btn-primary pull-right'
            ]
        ) ?>
    </fieldset>
<?php ActiveForm::end(); ?>