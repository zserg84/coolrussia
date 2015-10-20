<?php

namespace modules\users\controllers\frontend;

use common\models\City;
use common\models\Company;
use common\models\CompanyImage;
use modules\direction\models\Direction;
use common\models\State;
use common\models\UserHasCompany;
use modules\contract\models\Contract;
use modules\contract\models\ContractCustomer;
use modules\contract\models\ContractPerformer;
use modules\contract\models\ContractPerformerHasDirection;
use modules\contract\models\Tariff;
use modules\image\models\Image;
use modules\users\models\CustomerRegForm;
use modules\users\models\PerformerRegForm;
use vova07\fileapi\actions\UploadAction as FileAPIUpload;
use modules\users\models\frontend\ActivationForm;
use modules\users\models\frontend\RecoveryConfirmationForm;
use modules\users\models\frontend\RecoveryForm;
use modules\users\models\frontend\ResendForm;
use modules\users\models\frontend\User;
use modules\users\models\LoginForm;
use modules\users\Module;
use modules\themes\Module as ThemeModule;
use yii\captcha\CaptchaAction;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;
use Yii;

/**
 * Frontend controller for guest users.
 */
class GuestController extends Controller
{

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'fileapi-upload' => [
                'class' => FileAPIUpload::className(),
                'path' => $this->module->avatarsTempPath
            ],
            'captcha' => [
                'class' => CaptchaAction::className(),
                'backColor'=>0xFFFFFF, //цвет фона капчи
                'testLimit'=>2, //сколько раз капча не меняется
                'transparent'=>false,
                'foreColor'=>0xE16020, //цвет символов
            ],
        ];
    }


    /**
     * Sign Up page.
     * If record will be successful created, user will be redirected to home page.
     */
    public function actionSignup()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $this->layout = '//home';

        $customerModel = new CustomerRegForm;
        $performerModel = new PerformerRegForm();

        $user = new User(['scenario' => 'signup']);
        $post = Yii::$app->getRequest()->post();
        if(isset($post['form_type'])){
            switch($post['form_type']){
                case 'customer':
                    if(Yii::$app->getRequest()->isAjax){
                        if($ajaxValidate = $this->performAjaxValidation($customerModel)){
                            return $ajaxValidate;
                        }
                    }
                    elseif ($customerModel->load($post)) {
                        if ($customerModel->validate()) {
                            $user->setAttributes($customerModel->attributes);
                            $user->role = 'customer';
                            $user->status_id = User::STATUS_INACTIVE;
                            if($user->save()){
                                $user->logo = UploadedFile::getInstance($customerModel, 'logo');
                                $user->saveLogo();
                                $contract = new Contract();
                                $contract->city_id = $customerModel->city;
                                $contract->phone_country_code_1 = $customerModel->phone_country_code;
                                $contract->phone_city_code_1 = $customerModel->phone_city_code;
                                $contract->phone_num_1 = $customerModel->phone_num;
                                $contract->tariff_id = Tariff::DEFAULT_TARIFF;
                                $contract->customer_id = $user->id;
                                if($contract->save()){

                                }
                                if ($this->module->requireEmailConfirmation === true) {
                                    Yii::$app->session->setFlash(
                                        'ThemeModule',
                                        Module::t(
                                            'GUEST_INTERFACE',
                                            'FRONTEND_FLASH_CONFIRM_REGISTER',
                                            [
                                                'url' => Url::toRoute('resend')
                                            ]
                                        )
                                    );
                                }

                                return $this->goHome();
                            }
                            else{
                                VarDumper::dump($user->getErrors());
                            }
                        }
                        elseif (Yii::$app->request->isAjax) {
//                            Yii::$app->response->format = Response::FORMAT_JSON;
//                            return ActiveForm::validate($customerModel);
                        }
                    }
                    break;
                case 'performer':
                    if(Yii::$app->getRequest()->isAjax){
                        if($ajaxValidate = $this->performAjaxValidation($performerModel)){
                            return $ajaxValidate;
                        }
                    }
                    elseif ($performerModel->load($post)) {
                        if ($performerModel->validate()) {
                            $user->setAttributes($performerModel->attributes);
                            $user->name = $performerModel->company_name;
                            $user->role = 'performer';
                            $user->status_id = User::STATUS_INACTIVE;
                            if($user->save()){
                                $user->logo = UploadedFile::getInstance($performerModel, 'logo');
                                $user->saveLogo();

                                $company = new Company();
                                $company->setAttributes($performerModel->attributes);
                                $company->name = $performerModel->company_name;
                                $company->about = $performerModel->company_about;
                                $company->specialization = $performerModel->company_specialization;
                                $company->count_years = $performerModel->company_count_years;
                                $company->additional_info = $performerModel->additional_info;
                                $company->site = $performerModel->site;
                                $company->email_for_order = $performerModel->email_for_order;
                                if($company->save()){
//                                    $company->logo = UploadedFile::getInstance($performerModel, 'logo');
//                                    $company->saveLogo();
                                    $examples = UploadedFile::getInstances($performerModel, 'examples_of_works');
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
                                    $userCompany = new UserHasCompany();
                                    $userCompany->user_id = $user->id;
                                    $userCompany->company_id = $company->id;
                                    $userCompany->save();

                                    $contract = new Contract();
                                    $contract->setAttributes($performerModel->attributes);
                                    $contract->city_id = $performerModel->city;
                                    $contract->tariff_id = Tariff::DEFAULT_TARIFF;
                                    $contract->performer_id = $company->id;
                                    if($contract->save()){
//                                        $performerDirectionsMain = Yii::$app->getRequest()->post('performer-direction-spec-main');
                                        $performerDirectionsSecond = Yii::$app->getRequest()->post('performer-direction-spec-second');
                                        $performerDirectionsManufacturer = Yii::$app->getRequest()->post('performer-direction-equipment-manufacturer');
                                        $performerDirectionsModel = Yii::$app->getRequest()->post('performer-direction-equipment-model');
                                        $performerDirectionsField = Yii::$app->getRequest()->post('performer-direction-equipment-field');
                                        $performerDirectionsYear = Yii::$app->getRequest()->post('performer-direction-equipment-year');
                                        $performerDirectionsSecond = $performerDirectionsSecond ? $performerDirectionsSecond : [];
                                        foreach($performerDirectionsSecond as $i => $direction){
                                            $contractDirection = new ContractPerformerHasDirection();
                                            $contractDirection->contract_id = $contract->id;
                                            $contractDirection->direction_id = $direction;
                                            $contractDirection->equipment_manufacturer = $performerDirectionsManufacturer[$i];
                                            $contractDirection->equipment_model = $performerDirectionsModel[$i];
                                            $contractDirection->equipment_field = $performerDirectionsField[$i];
                                            $contractDirection->equipment_year = $performerDirectionsYear[$i];
                                            $contractDirection->save();
                                        }
                                    }else{
                                        VarDumper::dump($contract->getErrors());
                                    }
                                }
                                else{
                                    VarDumper::dump($company->getErrors());
                                }
                                if ($this->module->requireEmailConfirmation === true) {
                                    Yii::$app->session->setFlash(
                                        'success',
                                        ThemeModule::t(
                                            'GUEST_INTERFACE',
                                            'FRONTEND_FLASH_CONFIRM_REGISTER',
                                            [
                                                'url' => Url::toRoute('resend')
                                            ]
                                        )
                                    );
                                }
                                return $this->goHome();
                            }
                            else{
                                VarDumper::dump($user->getErrors());
                            }
                        }
                    }
                    break;
            }
        }

        return $this->render(
            'index',
            [
                'customerModel' => $customerModel,
                'performerModel' => $performerModel,
                'user' => $user,
            ]
        );
    }

    protected function performAjaxValidation($model)
    {
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model->setScenario('ajax');
            if(ActiveForm::validate($model))
                return ActiveForm::validate($model);
            return Json::encode(['output'=>'success']);
        }
        return false;
    }


    /**
     * Resend email confirmation token page.
     */
    public function actionResend()
    {
        $model = new ResendForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->resend()) {
                    Yii::$app->session->setFlash('success', Module::t('users', 'FRONTEND_FLASH_SUCCESS_RESEND'));
                    return $this->goHome();
                } else {
                    Yii::$app->session->setFlash('danger', Module::t('users', 'FRONTEND_FLASH_FAIL_RESEND'));
                    return $this->refresh();
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render(
            'resend',
            [
                'model' => $model
            ]
        );
    }

    /**
     * Sign In page.
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->login()) {
                    return $this->goHome();
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render(
            'login',
            [
                'model' => $model
            ]
        );
    }

    public function actionModallogin()
    {
        if (!Yii::$app->user->isGuest) {
            $this->goHome();
        }
        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->login()) {
                    return $this->goHome();
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }
        echo $this->renderPartial(
            'modallogin',
            [
                'model' => $model
            ]
        );
    }

    public function actionQuicklogin()
    {
        if (!Yii::$app->user->isGuest) {
            $this->goHome();
        }
        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->login()) {
                    $homeUrl = Yii::$app->getHomeUrl();
//                    return Yii::$app->getResponse()->redirect(Url::toRoute(['/'.Yii::$app->user->role.'/'.$homeUrl]));
                    return $this->goHome();
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode(['output'=>'error', 'error' => ActiveForm::validate($model)]);
//                return ActiveForm::validate($model);
            }
            else{
//                VarDumper::dump($model->getErrors());
//                return $this->redirect($_SERVER['HTTP_REFERER']);
            }
        }

        echo $this->renderPartial(
            'quicklogin',
            [
                'model' => $model
            ]
        );
    }

    /**
     * Activate a new user page.
     *
     * @param string $token Activation token.
     *
     * @return mixed View
     */
    public function actionActivation($token)
    {
        $model = new ActivationForm(['token' => $token]);

        if ($model->validate() && $model->activation()) {
//            Yii::$app->session->setFlash('success', Module::t('users', 'FRONTEND_FLASH_SUCCESS_ACTIVATION'));
        } else {
            Yii::$app->session->setFlash('danger', Module::t('users', 'FRONTEND_FLASH_FAIL_ACTIVATION'));
        }

        return $this->goHome();
    }

    /**
     * Request password recovery page.
     */
    public function actionRecovery()
    {
        $model = new RecoveryForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->recovery()) {
                    Yii::$app->session->setFlash('success', ThemeModule::t('GUEST_INTERFACE', 'FRONTEND_FLASH_SUCCESS_RECOVERY'));
                    return $this->goHome();
                } else {
                    Yii::$app->session->setFlash('danger', ThemeModule::t('GUEST_INTERFACE', 'FRONTEND_FLASH_FAIL_RECOVERY'));
                    return $this->refresh();
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->renderPartial(
            'recovery',
            [
                'model' => $model
            ]
        );
    }

    /**
     * Confirm password recovery request page.
     *
     * @param string $token Confirmation token
     *
     * @return mixed View
     */
    public function actionRecoveryConfirmation($token)
    {
        $model = new RecoveryConfirmationForm(['token' => $token]);

        if (!$model->isValidToken()) {
            Yii::$app->session->setFlash(
                'danger',
                Module::t('users', 'FRONTEND_FLASH_FAIL_RECOVERY_CONFIRMATION_WITH_INVALID_KEY')
            );
            return $this->goHome();
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->recovery()) {
                    $model->login();
                    Yii::$app->session->setFlash(
                        'success',
                        Module::t('users', 'FRONTEND_FLASH_SUCCESS_RECOVERY_CONFIRMATION')
                    );
                    return $this->goHome();
                } else {
                    Yii::$app->session->setFlash(
                        'danger',
                        Module::t('users', 'FRONTEND_FLASH_FAIL_RECOVERY_CONFIRMATION')
                    );
                    return $this->refresh();
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render(
            'recovery-confirmation',
            [
                'model' => $model
            ]
        );

    }


    public function actionGetStates() {
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $countryId = $parents[0];
                $states = State::find()->where(['country_id'=>$countryId])->all();
                $out = [];
                foreach($states as $k=>$state){
                    $out[$k] = [
                        'id' => $state->id,
                        'name' => $state->name,
                    ];
                }
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }

    public function actionGetCities() {
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $countryId = $parents[0];
                $stateId = isset($parents[1]) ? $parents[1] : null;
                $cities = City::find()->where(['country_id'=>$countryId]);
                if($stateId){
                    $cities->andWhere(['state_id' => $stateId]);
                }
                $cities = $cities->all();
                $out = [];
                foreach($cities as $k=>$city){
                    $out[$k] = [
                        'id' => $city->id,
                        'name' => $city->name,
                    ];
                }
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }

    public function actionGetSubdirection() {
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $directionId = $parents[0];
                $subs = Direction::find()->where(['parent_id'=>$directionId])->all();
                $out = [];
                foreach($subs as $k=>$sub){
                    $out[$k] = [
                        'id' => $sub->id,
                        'name' => $sub->name,
                    ];
                }
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }

    public function actionPerformersList(){

    }
}