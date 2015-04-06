<?php

/**
 * Faq form view.
 *
 * @var \yii\base\View $this View
 * @var \yii\widgets\ActiveForm $form Form
 * @var \modules\faq\models\Faq $model Model
 * @var \modules\themes\admin\widgets\Box $box Box widget instance
 * @var array $statusArray Statuses array
 */

use modules\page\Module;
use yii\helpers\Html;
use modules\page\models\Page;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;

?>
<?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
<?php $box->beginBody(); ?>
    <div class="row">
        <div class="col-sm-1">
            <?=$form->field($model, 'lang_id')->dropDownList(
                \modules\lang\models\Lang::getArr()
            ) ?>
        </div>
        <div class="col-sm-10">
            <?= $form->field($model, 'url') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'title') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'txt')->widget(
                Widget::className(),
                [
                    'settings' => [
                        'minHeight' => 300,
//                        'imageGetJson' => Url::to(['/blogs/default/imperavi-get']),
//                        'imageUpload' => Url::to(['/blogs/default/imperavi-image-upload']),
//                        'fileUpload' => Url::to(['/blogs/default/imperavi-file-upload'])
                    ]
                ]
            ) ?>
        </div>
    </div>

<?php $box->endBody(); ?>
<?php $box->beginFooter(); ?>
<?= Html::submitButton(
    $model->isNewRecord ? Module::t('page', 'BACKEND_CREATE_SUBMIT') : Module::t(
        'page',
        'BACKEND_UPDATE_SUBMIT'
    ),
    [
        'class' => $model->isNewRecord ? 'btn btn-primary btn-large' : 'btn btn-success btn-large'
    ]
) ?>
<?php $box->endFooter(); ?>
<?php ActiveForm::end(); ?>