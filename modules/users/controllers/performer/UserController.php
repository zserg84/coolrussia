<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 06.05.15
 * Time: 12:47
 */

namespace modules\users\controllers\performer;

use common\models\Company;
use common\models\CompanyImage;
use modules\contract\models\ContractPerformerHasDirection;
use modules\image\models\Image;
use Yii;
use modules\contract\models\Contract;
use modules\contract\models\Tariff;
use modules\users\models\performer\UserForm;
use modules\site\components\Controller;
use modules\users\models\frontend\User;
use yii\bootstrap\ActiveForm;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\Response;
use yii\web\UploadedFile;

class UserController extends Controller
{

    public function actionUpdate(){
        $userId = Yii::$app->getUser()->id;
        $post = Yii::$app->getRequest()->post();
        $model = new UserForm();
        $user = User::findOne($userId);
        $model->user = $user;
        $contracts = $user->performerContracts;
        $contract = $contracts? $contracts[0] : new Contract();
        if(!$post) {
            $model->setAttributes($user->attributes);
            $model->city = $contract->city_id;
            $model->state = $contract->city->state_id;
            $model->country = $contract->city->country_id;
            $model->phone_country_code_1 = $contract->phone_country_code_1;
            $model->phone_city_code_1 = $contract->phone_city_code_1;
            $model->phone_num_1 = $contract->phone_num_1;
            $model->phone_country_code_2 = $contract->phone_country_code_2;
            $model->phone_city_code_2 = $contract->phone_city_code_2;
            $model->phone_num_2 = $contract->phone_num_2;

            $company = $contract->performer;
            $companyImages = $company->companyImages;
            $model->examples_of_works = $companyImages;

            $model->setAttributes($company->attributes);
            $model->company_name = $company->name;
            $model->company_about = $company->about;
            $model->company_count_years = $company->count_years;
            $model->company_specialization = $company->specialization;
            $model->additional_info = $company->additional_info;
            $model->site = $company->site;

            $model->setAttributes($contract->attributes);
        }
        if(\Yii::$app->getRequest()->isAjax){
            $model->load($post);
            $model->user->login = $model->login;
            if($ajaxValidate = $this->performAjaxValidation($model)){
                return $ajaxValidate;
            }
        }
        elseif ($model->load($post)) {
            $model->user->login = $model->login;
            if ($model->validate()) {
                $user->setAttributes($model->attributes);
                if($user->save()){
                    $user->logo = UploadedFile::getInstance($model, 'logo');
                    $user->saveLogo();

                    $companies = $user->companies;
                    $company = $companies ? $companies[0] : new Company();
                    $company->setAttributes($model->attributes);
                    $company->name = $model->company_name;
                    $company->about = $model->company_about;
                    $company->specialization = $model->company_specialization;
                    $company->count_years = $model->company_count_years;
                    $company->additional_info = $model->additional_info;
                    $company->site = $model->site;
                    $company->email_for_order = $model->email_for_order;
                    if($company->save()){
//                        $company->logo = UploadedFile::getInstance($model, 'logo');
//                        $company->saveLogo();
                        $examples = UploadedFile::getInstances($model, 'examples_of_works');
                        foreach($examples as $example){
                            if (($tmpName = $example->tempName) and ($ext = $example->extension)) {
                                if ($image = Image::GetByFile($tmpName, $ext)) {
                                    $companyImage = new CompanyImage();
                                    $companyImage->image_id = $image->id;
                                    $companyImage->company_id = $company->id;
                                    $companyImage->save();
                                }
                            }
                        }

                        $contract->setAttributes($model->attributes);
                        $contract->city_id = $model->city;
                        $contract->phone_country_code_1 = $model->phone_country_code_1;
                        $contract->phone_city_code_1 = $model->phone_city_code_1;
                        $contract->phone_num_1 = $model->phone_num_1;
                        $contract->phone_country_code_2 = $model->phone_country_code_2;
                        $contract->phone_city_code_2 = $model->phone_city_code_2;
                        $contract->phone_num_2 = $model->phone_num_2;
                        $contract->performer_id = $company->id;
                        if($contract->save()){
                            $performerDirectionsSecond = Yii::$app->getRequest()->post('performer-direction-spec-second');
                            $performerDirectionsManufacturer = Yii::$app->getRequest()->post('performer-direction-equipment-manufacturer');
                            $performerDirectionsModel = Yii::$app->getRequest()->post('performer-direction-equipment-model');
                            $performerDirectionsField = Yii::$app->getRequest()->post('performer-direction-equipment-field');
                            $performerDirectionsYear = Yii::$app->getRequest()->post('performer-direction-equipment-year');
                            $performerDirectionsSecond = $performerDirectionsSecond ? $performerDirectionsSecond : [];
                            $cphds = [];
                            foreach($performerDirectionsSecond as $i => $direction){
                                $contractDirection = ContractPerformerHasDirection::find()->where([
                                    'contract_id' => $contract->id,
                                    'direction_id' => $direction,
                                ])->one();
                                $contractDirection = $contractDirection ? $contractDirection : new ContractPerformerHasDirection();
                                $contractDirection->contract_id = $contract->id;
                                $contractDirection->direction_id = $direction;
                                $contractDirection->equipment_manufacturer = $performerDirectionsManufacturer[$i];
                                $contractDirection->equipment_model = $performerDirectionsModel[$i];
                                $contractDirection->equipment_field = $performerDirectionsField[$i];
                                $contractDirection->equipment_year = $performerDirectionsYear[$i];
                                $cphds[$contractDirection->direction_id] = $contractDirection;
                            }

                            $existCphds = $contract->contractPerformerHasDirections;
                            /*Удаляем те, что не пришли в post*/
                            foreach($existCphds as $existCphd){
                                if(!isset($cphds[$existCphd->direction_id])){
                                    $existCphd->delete();
                                }
                            }
                            /*добавляем новые*/
                            foreach($cphds as $cphd){
                                if(!isset($existCphd[$cphd->direction_id])){
                                    $cphd->save();
                                }
                            }
                        }else{
                            VarDumper::dump($contract->getErrors());
                        }
                    }
                    else{
                        VarDumper::dump($company->getErrors());
                    }
                    return $this->redirect($_SERVER['HTTP_REFERER']);
                }
                else{
                    VarDumper::dump($user->getErrors());
                }
            }
        }

        return $this->renderAjax('update', [
            'model' => $model,
            'user' => $user,
            'contract' => $contract,
        ]);
    }

    public function actionImageDelete(){
        $id = Yii::$app->getRequest()->post('key');
        if($id){
            $image = Image::findOne($id);
            $image->delete();
            return true;
        }
    }

    protected function performAjaxValidation($model)
    {
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model->setScenario('ajax');
            if(ActiveForm::validate($model))
                return ActiveForm::validate($model);
        }
    }
} 