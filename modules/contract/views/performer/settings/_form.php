<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 07.05.15
 * Time: 16:53
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;
use common\models\Country;
use common\models\State;
use common\models\City;
use modules\contract\models\ContractSettingsRegion;

$form = ActiveForm::begin(
    [
        'action' => Url::toRoute(['/contract/settings/update']),
        'enableAjaxValidation' => true,
        'options' => [
            'id' => 'settings_form',
        ],
        'fieldClass'=>\common\components\ActiveField::className()
    ]
);?>
    <div class="popup-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <ul>
                    <li>
                        <!--Отправлять уведомления на E-mail о новых заказах в системе в соответствии с направлениями деятельности Вашей компании-->
                        <?=$form->field($model, 'notification_of_new_orders')->checkbox([
                            'label' => '<span>
                                <b>'.$model->getAttributeLabel('notification_of_new_orders').' </b>
                            </span>', 'labelOptions'=>['class'=>'costum-checkbox']
                        ])->label(false)?>
                    </li>
                    <li>
                        <!-- Отправлять уведомления на E-mail о заказах по которым Ваша компания выбрана исполнителем-->
                        <?=$form->field($model, 'notification_of_orders_company_performer')->checkbox([
                            'label' => '<span>
                                <b>'.$model->getAttributeLabel('notification_of_orders_company_performer').' </b>
                            </span>', 'labelOptions'=>['class'=>'costum-checkbox']
                        ])->label(false)?>
                    </li>
                    <li>
                        <!-- Отправлять уведомления на E-mail об откликах Исполнителей на заказ-->
                        <?=$form->field($model, 'notification_of_permormer_response')->checkbox([
                            'label' => '<span>
                                <b>'.$model->getAttributeLabel('notification_of_permormer_response').' </b>
                            </span>', 'labelOptions'=>['class'=>'costum-checkbox']
                        ])->label(false)?>
                    </li>
                    <li>
                        <!-- Применить при входе в Систему территориальный фильтр в соответствии с моим территориальным месторасположением-->
                        <?=$form->field($model, 'apply_filter_territory')->checkbox([
                            'label' => '<span>
                                <b>'.$model->getAttributeLabel("apply_filter_territory").'  </b>
                            </span>', 'labelOptions'=>['class'=>'costum-checkbox']
                        ])->label(false)?>
                    </li>
                    <li>
                        <!-- Кроме региона по умолчанию, дополнительно отображать заказы по следующим регионам-->
                        <?=$form->field($model, 'is_dop_regions')->checkbox([
                            'label' => '<span>
                                <b>'.$model->getAttributeLabel('is_dop_regions').' </b>
                            </span>', 'labelOptions'=>['class'=>'costum-checkbox'], 'onclick' => '
                                var checked = $(this).prop("checked");
                                var display = checked ? "" : "none";
                                $(".region-add").css("display", display);
                            ',
                        ])->label(false)?>
                    </li>
                </ul>
            </div>
        </div>
        <?
        $display = $model->is_dop_regions ? '' : 'none';
        ?>
        <div class="region-add" style="display: <?=$display?>">
            <?
            $settingsRegions = ContractSettingsRegion::find()->where([
                'setting_id' => $setting->id,
            ])->all();
            foreach($settingsRegions as $region):
                $fullRegionName = $region->country ? $region->country->name : '';
                $fullRegionName .= $region->state ? ', ' . $region->state->name : '';
                $fullRegionName .= $region->city ? ', ' . $region->city->name : '';
                ?>
                <div class="row">
                    <div class="col-sm-8 pull-right">
                        <input type="hidden" name="region-add-country[]" value="<?=$region->country_id?>" />
                        <input type="hidden" name="region-add-state[]" value="<?=$region->state_id?>" />
                        <input type="hidden" name="region-add-city[]" value="<?=$region->city_id?>" />
                        <p class="spec-string"><?=$fullRegionName?><span class="close-ico"></span></p>
                    </div>
                </div>
            <?endforeach?>
            <div class="row">
                <div class="col-sm-4">
                    <p><?=$model->getAttributeLabel('country')?><span>*</span></p>
                </div>
                <div class="col-sm-8 country-select">
                    <?=$form->field($model, 'country', ['options'=>['class'=>'']])->dropDownList(ArrayHelper::map(Country::find()->all(), 'id', 'name'), [
                        'id'=>'settingsform-country',
                        'class' => 'region-add-country',
                        'prompt' => $model->getAttributeLabel('country'),
                    ])->label(false)->error(false)?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <p><?=$model->getAttributeLabel('state')?><span>*</span></p>
                </div>
                <div class="col-sm-8 region-select">
                    <?= $form->field($model, 'state', ['options'=>['class'=>'']])->widget(DepDrop::classname(), [
                        'data'=> ArrayHelper::map(State::find()->where(['country_id'=>$model->country])->all(), 'id', 'name'),
                        'options'=>[
                            'id'=>'settingsform-state',
                            'prompt' => $model->getAttributeLabel('state'),
                            'class' => 'region-add-state',
                        ],
                        'pluginOptions'=>[
                            'depends'=>['settingsform-country'],
                            'placeholder' => $model->getAttributeLabel('state'),
                            'url'=>'/users/guest/get-states/',
                        ],
                        'pluginEvents' => [
                            "depdrop.afterChange"=>"function(event, id, value) {
                        var oDropdown = $('#settingsform-state').msDropdown().data('dd');
                        oDropdown.destroy();
                        oDropdown = $('#settingsform-state').msDropdown().data('dd');
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
                <div class="col-sm-8 city-select">
                    <?
                    $cities = City::find();
                    if($model->country)
                        $cities->andWhere(['country_id'=>$model->country]);
                    if($model->state)
                        $cities->andWhere(['state_id'=>$model->state]);
                    $cities = $cities->all();
                    echo $form->field($model, 'city', ['options'=>['class'=>'']])->widget(DepDrop::classname(), [
                        'data'=> ArrayHelper::map($cities, 'id', 'name'),
                        'options' => [
                            'id'=>'settingsform-city',
                            'placeholder' => $model->getAttributeLabel('city'),
                            'class' => 'region-add-city',
                        ],
                        'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                        'pluginOptions'=>[
                            'depends'=>['settingsform-country', 'settingsform-state'],
                            'url' => '/users/guest/get-cities/',
                            'placeholder' => $model->getAttributeLabel('city'),
                        ],
                        'pluginEvents' => [
                            "depdrop.afterChange"=>"function(event, id, value) {
                        var oDropdown = $('#settingsform-city').msDropdown().data('dd');
                        oDropdown.destroy();
                        oDropdown = $('#settingsform-city').msDropdown().data('dd');
                        oDropdown.refresh();
                    }",
                        ],
                    ])->label(false)->error(false);
                    ?>
                </div>
            </div>

            <div class="row setting-btn">
                <div class="col-sm-4">
                    <p>&nbsp;</p>
                </div>
                <div class="col-sm-8 ">
                    <a class="add button" href="#">Добавить</a>
                </div>
            </div>

        </div>
    </div>
<?ActiveForm::end();
