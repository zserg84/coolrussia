<?php

/**
 * Faqlang form view.
 *
 * @var \yii\base\View $this View
 * @var \yii\widgets\ActiveForm $form Form
 * @var \modules\faq\models\FaqLang $model Model
 * @var \modules\themes\admin\widgets\Box $box Box widget instance
 * @var array $statusArray Statuses array
 */

use modules\faq\Module;
use vova07\imperavi\Widget as Imperavi;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

?>
<?php $form = ActiveForm::begin(); ?>
<?php $box->beginBody(); ?>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'lang_id')->dropDownList(ArrayHelper::map($langArr, 'id', 'name'),[

            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'request')->widget(
                Imperavi::className(),
                [
                    'settings' => [
                        'minHeight' => 200,
                        'imageGetJson' => Url::to(['/faq/faqlang/imperavi-get']),
                        'imageUpload' => Url::to(['/faq/faqlang/imperavi-image-upload']),
                        'fileUpload' => Url::to(['/faq/faqlang/imperavi-file-upload'])
                    ]
                ]
            ) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'response')->widget(
                Imperavi::className(),
                [
                    'settings' => [
                        'minHeight' => 300,
                        'imageGetJson' => Url::to(['/faq/faqlang/imperavi-get']),
                        'imageUpload' => Url::to(['/faq/faqlang/imperavi-image-upload']),
                        'fileUpload' => Url::to(['/faq/faqlang/imperavi-file-upload'])
                    ]
                ]
            ) ?>
        </div>
    </div>
<?php $box->endBody(); ?>
<?php $box->beginFooter(); ?>
<?= Html::submitButton(
    $model->isNewRecord ? Module::t('faqlang', 'BACKEND_CREATE_SUBMIT') : Module::t(
        'faqlang',
        'BACKEND_UPDATE_SUBMIT'
    ),
    [
        'class' => $model->isNewRecord ? 'btn btn-primary btn-large' : 'btn btn-success btn-large'
    ]
) ?>
<?php $box->endFooter(); ?>
<?php ActiveForm::end(); ?>