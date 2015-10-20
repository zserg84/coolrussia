<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 07.05.15
 * Time: 16:48
 */

namespace modules\contract\controllers;

use modules\contract\models\Contract;
use modules\contract\models\ContractSettings;
use modules\contract\models\ContractSettingsRegion;
use modules\contract\models\form\SettingsForm;
use modules\site\components\Controller;
use yii\helpers\VarDumper;

abstract class SettingsController extends Controller
{

    public function actionUpdate(){
        $curContract = Contract::getCurContract();
        $model = $this->getSettingsForm();
        $post = \Yii::$app->getRequest()->post();
        $setting = $curContract->getContractSetting();
        $setting = $setting ? $setting : new ContractSettings();
        $model->setAttributes($setting->attributes);
        if (\Yii::$app->request->isAjax) {
            if($errors = $this->performAjaxValidation($model))
                return $errors;
            return true;
        }
        elseif($post && $model->load($post)){
            $setting->setAttributes($model->attributes);
            $setting->contract_id = $curContract->id;
            if($setting->save()){
                $countries = \Yii::$app->getRequest()->post('region-add-country', []);
                $states = \Yii::$app->getRequest()->post('region-add-state', []);
                $cities = \Yii::$app->getRequest()->post('region-add-city', []);
                $settingsRegions = [];
                foreach($countries as $i => $country){
                    $state = isset($states[$i]) ? $states[$i] : null;
                    $city = isset($cities[$i]) ? $cities[$i] : null;
                    $settingsRegion = ContractSettingsRegion::find()->where([
                        'setting_id'=>$setting->id,
                        'country_id' => $country,
                        'state_id' => $state,
                        'city_id' => $city
                    ])->one();
                    $settingsRegion = $settingsRegion ? $settingsRegion : new ContractSettingsRegion();
                    $settingsRegion->setting_id = $setting->id;
                    $settingsRegion->country_id = $country;
                    $settingsRegion->state_id = $state;
                    $settingsRegion->city_id = $city;
                    $id = $settingsRegion->id ? $settingsRegion->id : 'new_'.$i;
                    $settingsRegions[$id] = $settingsRegion;
                }

                $existSettingsRegions = $setting->contractSettingsRegions;
                /*Удаляем те, что не пришли в post*/
                foreach($existSettingsRegions as $existRegion){
                    if(!isset($settingsRegions[$existRegion->id])){
                        $existRegion->delete();
                    }
                }
                /*добавляем новые*/
                foreach($settingsRegions as $settingsRegion){
                    if(!isset($settingsRegions[$settingsRegion->id])){
                        $settingsRegion->save();
                    }
                }
            }
            $this->redirect($_SERVER['HTTP_REFERER']);
        }
        return $this->renderPartial('update', [
            'model' => $model,
            'setting' => $setting,
        ]);
    }

    abstract protected function getSettingsForm();
} 