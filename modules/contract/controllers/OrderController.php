<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 24.04.15
 * Time: 10:30
 */

namespace modules\contract\controllers;

use modules\contract\models\form\OrderFormCreate;
use modules\contract\models\form\OrderFormUpdate;
use modules\contract\models\OfferToPerformer;
use modules\site\components\Controller;
use modules\users\models\User;
use Yii;
use modules\contract\models\Contract;
use modules\contract\models\form\OrderForm;
use modules\contract\models\Order;
use modules\contract\models\OrderHasDirection;
use modules\contract\models\search\OrderSearch;
use yii\bootstrap\ActiveForm;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\Response;
use yii\web\UploadedFile;

class OrderController extends Controller
{
    protected  $_buttons = [];

    public function getButtons(){
        return $this->_buttons;
    }

    public function actionList(){
        $dataProvider = $this->getOrderList();
        return $this->render('list', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function getOrderList($params = []){
        $model = new OrderSearch();
        foreach($params as $paramKey=>$paramValue){
            $model->$paramKey = $paramValue;
        }
        return $model->search();
    }

    public function actionListMine(){
        $contract = Contract::getCurContract();
        $dataProvider = $this->getOrderList([
            'contract_id' => $contract->id,
        ]);
        return $this->render('list',[
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate(){
        $model = new OrderFormCreate();
        $order = new Order();
        if($errors = $this->edit($model, $order))
            return $errors;

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate(){
        $orderId = Yii::$app->getRequest()->post('orderId');
        $model = new OrderFormUpdate();
        $order = Order::findOne($orderId);
        $model->setAttributes($order->attributes);//VarDumper::dump($model,10,1);exit;

        if($errors = $this->edit($model, $order))
            return $errors;

        $model->orderId = $orderId;

        return $this->renderAjax('_create_modal', [
            'model' => $model,
        ]);
    }

    public function edit($model, $order){//VarDumper::dump($_POST);exit;
        if (Yii::$app->request->isAjax) {
            if($errors = $this->performAjaxValidation($model))
                return $errors;
        }
        else
            if($model->load(\Yii::$app->getRequest()->post())){
            if($model->validate()){
                $curContract = Contract::getCurContract();
                $order->setAttributes($model->attributes);
                $order->contract_id = $curContract->id;

                if($order->save()){
                    $order->image = UploadedFile::getInstance($model, 'image_id');
                    $order->saveLogo();

                    $order->image = UploadedFile::getInstance($model, 'file_model_id');
                    $order->saveFileModel();

                    $orderDirectionsSecond = Yii::$app->getRequest()->post('order-direction-spec-second');
                    $orderDirectionsSecond = $orderDirectionsSecond ? $orderDirectionsSecond : [];
                    $ohds = [];
                    foreach($orderDirectionsSecond as $i => $direction){
                        $orderDirection = OrderHasDirection::find()->where([
                            'order_id' => $order->id,
                            'direction_id' => $direction,
                        ])->one();
                        $orderDirection = $orderDirection ? $orderDirection : new OrderHasDirection();
                        $orderDirection->order_id = $order->id;
                        $orderDirection->direction_id = $direction;
                        $ohds[$orderDirection->direction_id] = $orderDirection;
//                        $orderDirection->save();
                    }
                    $existOdhs = $order->orderHasDirections;

                    /*Удаляем те, что не пришли в post*/
                    foreach($existOdhs as $existOdh){
                        if(!isset($ohds[$existOdh->direction_id])){
                            $existOdh->delete();
                        }
                    }
                    /*добавляем новые*/
                    foreach($ohds as $ohd){
                        if(!isset($existOdh[$ohd->direction_id])){
                            $ohd->save();
                        }
                    }
                    return $this->redirect($_SERVER['HTTP_REFERER']);
//                    return Json::encode(['output'=>'success']);
                }
                else{
                    VarDumper::dump($order->getErrors());
                }
            }
            else{
//                return Json::encode(['output'=>'error', 'error' => ActiveForm::validate($model)]);
            }
        }
        return false;
    }

    public function actionView($orderId){
        $model = Order::findOne($orderId);
        echo $this->renderAjax('_order_view', [
            'model' => $model,
        ]);
    }

    public function actionOfferOrder(){
        $contractId = \Yii::$app->getRequest()->post('contractId');
        $orderId = \Yii::$app->getRequest()->post('orderId');

        if(\Yii::$app->getRequest()->post()){
            $otp = OfferToPerformer::find()->where([
                'contract_id' => $contractId,
                'order_id' => $orderId,
            ])->one();
            if(!$otp){
                $otp = new OfferToPerformer();
                $otp->order_id = $orderId;
                $otp->contract_id = $contractId;
                $otp->save();
            }
            $this->redirect(Url::toRoute(['list']));
        }
    }

    protected function performAjaxValidation($model)
    {
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model->setScenario('ajax');
            if(ActiveForm::validate($model))
                return Json::encode(ActiveForm::validate($model));
//            \Yii::$app->end();
        }
    }
} 