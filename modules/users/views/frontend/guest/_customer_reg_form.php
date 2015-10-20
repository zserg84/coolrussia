<?php
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\ArrayHelper;
use common\models\Country;
use common\models\State;
use common\models\City;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use \yii\helpers\Html;

$form = ActiveForm::begin(
    [
        'enableAjaxValidation' => true,
        'enableClientValidation' => true,
        'validateOnBlur' => false,
        'options' => [
            'id' => 'customer_reg_form',
            'enctype' => 'multipart/form-data',
            'data-pjax' => true,
        ],
    ]
);
echo Html::hiddenInput('form_type', 'customer');
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
            <p><?=$model->getAttributeLabel('name')?><span>*</span></p>
        </div>
        <div class="col-sm-8">
            <?=$form->field($model, 'name')->textInput(['class'=>''])->label(false)->error(false)?>
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
            <p><?=$model->getAttributeLabel('phone')?><span>*</span></p>
        </div>
        <div class="col-sm-2">
            <?=$form->field($model, 'phone_country_code')->textInput(['class'=>'', 'placeholder'=>$model->getAttributeLabel('phone_country_code')])->label(false)->error(false)?>
        </div>
        <div class="col-sm-2">
            <?=$form->field($model, 'phone_city_code')->textInput(['class'=>'', 'placeholder'=>$model->getAttributeLabel('phone_city_code')])->label(false)->error(false)?>
        </div>
        <div class="col-sm-3">
            <?=$form->field($model, 'phone_num')->textInput(['class'=>'', 'placeholder'=>$model->getAttributeLabel('phone_num')])->label(false)->error(false)?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <p><?=$model->getAttributeLabel('country')?><span>*</span></p>
        </div>
        <div class="col-sm-8">
            <?=$form->field($model, 'country')->dropDownList(ArrayHelper::map(Country::find()->all(), 'id', 'name'), [
                'id'=>'customerregform-country',
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
                    'id'=>'customerregform-state',
                    'prompt' => $model->getAttributeLabel('state'),
                ],
                'pluginOptions'=>[
                    'depends'=>['customerregform-country'],
                    'placeholder' => $model->getAttributeLabel('state'),
                    'url'=>Url::to(['/users/guest/get-states']),
                ],
                'pluginEvents' => [
                    "depdrop.afterChange"=>"function(event, id, value) {
                        var oDropdown = $('#customerregform-state').msDropdown().data('dd');
                        oDropdown.destroy();
                        oDropdown = $('#customerregform-state').msDropdown().data('dd');
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
                    'id'=>'customerregform-city',
                    'placeholder' => $model->getAttributeLabel('city'),
                ],
                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                    'depends'=>['customerregform-country', 'customer-state-id'],
                    'url' => Url::to(['/users/guest/get-cities']),
                    'loadingText' => $model->getAttributeLabel('city'),
                ],
                'pluginEvents' => [
                    "depdrop.afterChange"=>"function(event, id, value) {
                        var oDropdown = $('#customerregform-city').msDropdown().data('dd');
                        oDropdown.destroy();
                        oDropdown = $('#customerregform-city').msDropdown().data('dd');
                        oDropdown.refresh();
                    }",
                ],
            ])->label(false)->error(false);
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <p><?=$model->getAttributeLabel('logo')?></p>
        </div>
        <div class="col-sm-8">
            <?=$form->field($model, 'logo')->fileInput(['id' => 'costum-file-avatar'])->label(false)->error(false)?>
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
<?ActiveForm::end()?>