<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\models\City;
use common\models\State;
use common\models\Country;
use modules\direction\models\Direction;
use kartik\depdrop\DepDrop;
use yii\captcha\Captcha;
use vova07\fileapi\Widget as FileApi;

$form = ActiveForm::begin(
    [
        'enableAjaxValidation' => true,
        'enableClientValidation' => true,
        'options' => [
            'id' => 'performer_reg_form',
            'enctype' => 'multipart/form-data',
        ]
    ]
);
echo Html::hiddenInput('form_type', 'performer');
?>
<div class="popup-wrapper">
    <div class="row">
        <div class="col-sm-4">
            <p><?=$model->getAttributeLabel('registration_date')?></p>
        </div>
        <div class="col-sm-8">
            <p><?=date('d.m.Y')?></p>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <p><?=$model->getAttributeLabel('login')?><span>*</span></p>
        </div>
        <div class="col-sm-8">
            <?=$form->field($model, 'login')->textInput(['class'=>''])->label(false)->error(false)?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <p><?=$model->getAttributeLabel('password')?><span>*</span></p>
        </div>
        <div class="col-sm-8">
            <?=$form->field($model, 'password')->textInput(['class'=>''])->label(false)->error(false)?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <p><?=$model->getAttributeLabel('repassword')?><span>*</span></p>
        </div>
        <div class="col-sm-8">
            <?=$form->field($model, 'repassword')->textInput(['class'=>''])->label(false)->error(false)?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <p><?=$model->getAttributeLabel('company_name')?><span>*</span></p>
        </div>
        <div class="col-sm-8">
            <?=$form->field($model, 'company_name')->textInput(['class'=>''])->label(false)->error(false)?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <p><?=$model->getAttributeLabel('company_about')?><span>*</span></p>
        </div>
        <div class="col-sm-8">
            <?=$form->field($model, 'company_about')->textInput(['class'=>''])->label(false)->error(false)?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <p><?=$model->getAttributeLabel('company_specialization')?><span>*</span></p>
        </div>
        <div class="col-sm-8">
            <?=$form->field($model, 'company_specialization')->textInput(['class'=>''])->label(false)->error(false)?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <p><?=$model->getAttributeLabel('company_count_years')?><span>*</span></p>
        </div>
        <div class="col-sm-2">
            <?=$form->field($model, 'company_count_years')->dropDownList(range(1, 25), ['prompt'=>''])->label(false)->error(false)?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <p><?=$model->getAttributeLabel('additional_info')?><span>*</span></p>
        </div>
        <div class="col-sm-8">
            <?=$form->field($model, 'additional_info')->textarea(['rows'=>5])->label(false)->error(false)?>
        </div>
    </div>

    <div class="row margin0">
        <div class="col-sm-4">
            <p><?=$model->getAttributeLabel('directions')?><span>*</span></p>
        </div>
        <div class="col-sm-8 performer-direction">
            <div class="direction-block col-sm-12">
                <div class="row">
                    <?$directions = ArrayHelper::map(Direction::find()->andWhere(['parent_id'=>null])->all(), 'id', 'name')?>
                    <div class="col-sm-6 spec-main-select">
                        <?=Html::dropDownList('select-spec-main-select' ,false, $directions, [
                            'id'=>'performer-direction-id',
                        ])?>
                    </div>
                    <div class="col-sm-6 spec-second-select">
                        <?
                        echo DepDrop::widget([
                            'name' => 'performer-subdirection',
                            'data' => ArrayHelper::map(Direction::find()->where(['parent_id' => key($directions)])->all(), 'id', 'name'),
                            'options'=>[
                                'id'=>'performer-subdirection-id',
                            ],
                            'pluginOptions'=>[
                                'depends'=>['performer-direction-id'],
                                'url'=>Url::to(['/users/guest/get-subdirection']),
                            ],
                            'pluginEvents' => [
                                "depdrop.afterChange" => "function(event, id, value) {
                                    var oDropdown = $('#performer-subdirection-id').msDropdown().data('dd');
                                    oDropdown.destroy();
                                    oDropdown = $('#performer-subdirection-id').msDropdown().data('dd');
                                    oDropdown.refresh();
                                }
                                "
                            ]
                        ]);
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <input type="text" name="" placeholder="производитель оборудования" id="performer-equipment-manufacturer"/>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="" placeholder="модель оборудования" id="performer-equipment-model"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <input type="text" name="" placeholder="рабочее поле в мм" id="performer-equipment-field"/>
                    </div>
                    <div class="col-sm-3">
<!--                        <input type="text" name="" placeholder="год выпуска" class="datepicker" id="performer-equipment-year"/>-->
                        <input type="text" name="" placeholder="год выпуска"  id="performer-equipment-year"/>
                    </div>
                    <div class="col-sm-3 add">
                        <a href="#">Добавить +</a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-sm-4">
            <p><?=$model->getAttributeLabel('site')?><span>*</span></p>
        </div>
        <div class="col-sm-8">
            <?=$form->field($model, 'site')->textInput(['class'=>''])->label(false)->error(false)?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <p><?=$model->getAttributeLabel('email')?><span>*</span></p>
        </div>
        <div class="col-sm-8">
            <?=$form->field($model, 'email')->textInput(['class'=>''])->label(false)->error(false)?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <p><?=$model->getAttributeLabel('phone_1')?><span>*</span></p>
        </div>
        <div class="col-sm-2">
            <?=$form->field($model, 'phone_country_code_1')->textInput(['class'=>'', 'placeholder'=>$model->getAttributeLabel('phone_country_code_1')])->label(false)->error(false)?>
        </div>
        <div class="col-sm-2">
            <?=$form->field($model, 'phone_city_code_1')->textInput(['class'=>'', 'placeholder'=>$model->getAttributeLabel('phone_city_code_1')])->label(false)->error(false)?>
        </div>
        <div class="col-sm-3">
            <?=$form->field($model, 'phone_num_1')->textInput(['class'=>'', 'placeholder'=>$model->getAttributeLabel('phone_num_1')])->label(false)->error(false)?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <p><?=$model->getAttributeLabel('phone_2')?><span>*</span></p>
        </div>
        <div class="col-sm-2">
            <?=$form->field($model, 'phone_country_code_2')->textInput(['class'=>'', 'placeholder'=>$model->getAttributeLabel('phone_country_code_2')])->label(false)->error(false)?>
        </div>
        <div class="col-sm-2">
            <?=$form->field($model, 'phone_city_code_2')->textInput(['class'=>'', 'placeholder'=>$model->getAttributeLabel('phone_city_code_2')])->label(false)->error(false)?>
        </div>
        <div class="col-sm-3">
            <?=$form->field($model, 'phone_num_2')->textInput(['class'=>'', 'placeholder'=>$model->getAttributeLabel('phone_num_2')])->label(false)->error(false)?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <p><?=$model->getAttributeLabel('country')?><span>*</span></p>
        </div>
        <div class="col-sm-8">
            <?=$form->field($model, 'country')->dropDownList(ArrayHelper::map(Country::find()->all(), 'id', 'name'), [
                'id'=>'performerregform-country',
                'prompt' => $model->getAttributeLabel('country'),
            ])->label(false)->error(false)?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <p><?=$model->getAttributeLabel('state')?><span>*</span></p>
        </div>
        <div class="col-sm-8">
            <?= $form->field($model, 'state')->widget(DepDrop::classname(), [
                'data'=> ArrayHelper::map(State::find()->where(['country_id'=>$model->country])->all(), 'id', 'name'),
                'options'=>[
                    'id'=>'performerregform-state',
                    'prompt' => $model->getAttributeLabel('state'),
                ],
                'pluginOptions'=>[
                    'depends'=>['performerregform-country'],
                    'placeholder' => $model->getAttributeLabel('state'),
                    'url'=>Url::to(['/users/guest/get-states']),
                ],
                'pluginEvents' => [
                    "depdrop.afterChange"=>"function(event, id, value) {
                        var oDropdown = $('#performerregform-state').msDropdown().data('dd');
                        oDropdown.destroy();
                        oDropdown = $('#performerregform-state').msDropdown().data('dd');
                        oDropdown.refresh();
                    }",
                ],
            ])->label(false)->error(false);?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <p><?=$model->getAttributeLabel('city')?><span>*</span></p>
        </div>
        <div class="col-sm-8">
            <?
            $cities = City::find();
            if($model->country)
                $cities->andWhere(['country_id'=>$model->country]);
            if($model->state)
                $cities->andWhere(['state_id'=>$model->state]);
            $cities = $cities->all();
            echo $form->field($model, 'city')->widget(DepDrop::classname(), [
                'data'=> ArrayHelper::map($cities, 'id', 'name'),
                'options' => [
                    'id'=>'performerregform-city',
                    'placeholder' => $model->getAttributeLabel('city'),
                ],
                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                    'depends'=>['performerregform-country', 'performerregform-state'],
                    'url' => Url::to(['/users/guest/get-cities']),
                    'placeholder' => $model->getAttributeLabel('city'),
                ],
                'pluginEvents' => [
                    "depdrop.afterChange"=>"function(event, id, value) {
                        var oDropdown = $('#performerregform-city').msDropdown().data('dd');
                        oDropdown.destroy();
                        oDropdown = $('#performerregform-city').msDropdown().data('dd');
                        oDropdown.refresh();
                    }",
                ],
            ])->label(false)->error(false);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <p><?=$model->getAttributeLabel('street')?><span>*</span></p>
        </div>
        <div class="col-sm-8">
            <?=$form->field($model, 'street')->textInput(['class'=>'', 'placeholder' => 'фактический адрес местонахождения производства'])->label(false)->error(false)?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <p><?=$model->getAttributeLabel('house')?></p>
        </div>
        <div class="col-sm-8">
            <?=$form->field($model, 'house')->textInput(['class'=>''])->label(false)->error(false)?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <p><?=$model->getAttributeLabel('corpus')?></p>
        </div>
        <div class="col-sm-8">
            <?=$form->field($model, 'corpus')->textInput(['class'=>''])->label(false)->error(false)?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <p><?=$model->getAttributeLabel('building')?></p>
        </div>
        <div class="col-sm-8">
            <?=$form->field($model, 'building')->textInput(['class'=>''])->label(false)->error(false)?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <p><?=$model->getAttributeLabel('logo')?></p>
        </div>
        <div class="col-sm-8">
            <?=$form->field($model, 'logo')->fileInput(['id' => 'costum-file-logo'])->label(false)->error(false)?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <p><?=$model->getAttributeLabel('examples_of_works')?></p>
        </div>
        <div class="col-sm-8">
            <div class="row ">
                <div class="col-sm-12">
                    <?= $form->field($model, 'examples_of_works[]')->widget(\dosamigos\fileinput\BootstrapFileInput::className(), [
                        'options' => ['accept' => 'image/*', 'multiple' => true],
                        'clientOptions' => [
                            'previewFileType' => 'text',
                            'browseClass' => 'btn btn-success',
                            'uploadClass' => 'btn btn-info',
                            'removeClass' => 'btn btn-danger',
                            'removeIcon' => '<i class="glyphicon glyphicon-trash"></i> ',
                            'showUpload' => false,
                        ]
                    ])->label(false)->error(false);?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <p><?=$model->getAttributeLabel('email_for_order')?><span>*</span></p>
        </div>
        <div class="col-sm-8">
            <?=$form->field($model, 'email_for_order')->textInput(['class'=>''])->label(false)->error(false)?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <?=$form->field($model, 'confirm')->checkbox()?>
        </div>
    </div>

    <div class="row capcha">
        <div class="col-sm-4">
            <?= $form->field($model, 'captcha')->widget(Captcha::className(), [
                'captchaAction'=>'/site/default/captcha'
            ])->error(false) ?>
        </div>
    </div>

</div>

<?=Html::submitButton($model->getAttributeLabel('submit_button')); ?>
<button class="mfp-close-btn"><?=$model->getAttributeLabel('cancel_button')?></button>
<? ActiveForm::end();