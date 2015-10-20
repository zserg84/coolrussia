<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 06.05.15
 * Time: 12:47
 */

namespace modules\users\controllers\customer;

use Yii;
use modules\contract\models\Contract;
use modules\contract\models\Tariff;
use modules\users\models\customer\UserForm;
use modules\site\components\Controller;
use modules\users\models\frontend\User;
use yii\helpers\Url;
use yii\web\UploadedFile;

class UserController extends Controller
{

    public function actionUpdate(){
        $userId = Yii::$app->getUser()->id;
        $post = Yii::$app->getRequest()->post();
        $model = new UserForm();
        $user = User::findOne($userId);
        $contracts = $user->customerContracts;
        $contract = $contracts? $contracts[0] : new Contract();
        if(!$post) {
            $model->setAttributes($user->attributes);
            $model->city = $contract->city_id;
            $model->state = $contract->city->state_id;
            $model->country = $contract->city->country_id;
            $model->phone_country_code = $contract->phone_country_code_1;
            $model->phone_city_code = $contract->phone_city_code_1;
            $model->phone_num = $contract->phone_num_1;
        }
        if(\Yii::$app->getRequest()->isAjax){
            if($ajaxValidate = $this->performAjaxValidation($model)){
                return $ajaxValidate;
            }
        }
        elseif ($model->load($post)) {
            if ($model->validate()) {
                $user->setAttributes($model->attributes);
                if($user->save()){
                    $logo = UploadedFile::getInstance($model, 'logo');
                    $user->logo = $logo ? $logo : $user->logo;
                    $user->saveLogo();


                    $contract->city_id = $model->city;
                    $contract->phone_country_code_1 = $model->phone_country_code;
                    $contract->phone_city_code_1 = $model->phone_city_code;
                    $contract->phone_num_1 = $model->phone_num;
                    $contract->tariff_id = $contract->tariff_id ? $contract->tariff_id : Tariff::DEFAULT_TARIFF;
                    $contract->customer_id = $user->id;
                    if($contract->save()){

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
        ]);
    }
} 