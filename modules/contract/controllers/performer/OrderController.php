<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 24.04.15
 * Time: 10:29
 */

namespace modules\contract\controllers\performer;

use modules\contract\models\Contract;
use modules\contract\models\OfferToCustomer;
use modules\contract\models\Order;
use modules\contract\widgets\actionButtons\orders\OrderLinkButton;
use modules\contract\widgets\actionButtons\orders\ResponseDeleteButton;
use modules\contract\widgets\actionButtons\orders\ResponseOrderLinkButton;
use modules\contract\widgets\actionButtons\orders\UpdateButton;
use performer\components\PerformerTrait;
use yii\bootstrap\ActiveForm;
use yii\helpers\Json;
use yii\helpers\Url;

class OrderController extends \modules\contract\controllers\OrderController
{

    use PerformerTrait;

    public function actionList(){
        $this->_buttons = [
            OrderLinkButton::className(),
            ResponseOrderLinkButton::className(),
        ];

        return parent::actionList();
    }

    public function actionListMyResponse(){
        $this->_buttons = [
            OrderLinkButton::className(),
            ResponseDeleteButton::className(),
        ];

        $dataProvider = $this->getOrderList([
            'withMyResponse' => true,
        ]);
        return $this->render('list',[
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionListMine(){
        $this->_buttons = [
            UpdateButton::className(),
        ];

        return parent::actionListMine();
    }

    public function actionResponse($orderId){
        $order = Order::findOne($orderId);
        $model = new OfferToCustomer();

//        $this->performAjaxValidation($model);

        if($model->load(\Yii::$app->getRequest()->post())){
            $curContract = Contract::getCurContract();
            $offerToCustomer = OfferToCustomer::find()->where([
                'order_id' => $orderId,
                'contract_id' => $curContract->id,
            ])->one();
            if($offerToCustomer){
                return $this->redirect(Url::toRoute(['list']));
            }
            $model->contract_id = $curContract->id;
            $model->order_id = $orderId;
            if($model->save()){
                return $this->redirect(Url::toRoute(['list']));
            }
            else{
                return Json::encode([ActiveForm::validate($model)]);
            }
        }

        echo $this->renderAjax('response', [
            'model' => $model,
            'order' => $order,
        ]);
    }

    public function actionResponseDelete($orderId){
        $curContract = Contract::getCurContract();
        $offerToCustomer = OfferToCustomer::find()->where([
            'order_id' => $orderId,
            'contract_id' => $curContract->id,
        ])->one();
        $offerToCustomer->delete();

        return $this->redirect(Url::toRoute(['list-my-response']));
    }
} 