<?php

/**
 * Geo form view.
 *
 * @var \yii\base\View $this View
 * @var \yii\widgets\ActiveForm $form Form
 * @var \modules\faq\models\Geo $model Model
 * @var \modules\themes\admin\widgets\Box $box Box widget instance
 * @var array $statusArray Statuses array
 */

use modules\geo\Module;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use modules\geo\models\GeoCountry;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

$regions = ArrayHelper::map(GeoCountry::findOne(1)->geoRegions, 'id', 'name_ru');
?>
<?php $form = ActiveForm::begin(); ?>
<?php $box->beginBody(); ?>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'country_id')->dropDownList(ArrayHelper::map(GeoCountry::find()->all(), 'id', 'name_ru'), [
                'onchange' => '
                    $.ajax
                ',
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?
            echo $form->field($model, 'region_id')->widget(DepDrop::classname(), [
                'data'=> $regions,
                'options' => ['placeholder' => Module::t('geo-region', 'SELECT_REGION')],
                'type' => DepDrop::TYPE_SELECT2,
                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                    'depends'=>['geocity-country_id'],
                    'url' => Url::to(['/geo/country/get-regions']),
                ],
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'name_ru')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'name_en')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'sort')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'confirm')->checkbox() ?>
        </div>
    </div>
<?php $box->endBody(); ?>
<?php $box->beginFooter(); ?>
<?= Html::submitButton(
    $model->isNewRecord ? Module::t('geo', 'BACKEND_CREATE_SUBMIT') : Module::t(
        'geo',
        'BACKEND_UPDATE_SUBMIT'
    ),
    [
        'class' => $model->isNewRecord ? 'btn btn-primary btn-large' : 'btn btn-success btn-large'
    ]
) ?>
<?php $box->endFooter(); ?>
<?php ActiveForm::end(); ?>