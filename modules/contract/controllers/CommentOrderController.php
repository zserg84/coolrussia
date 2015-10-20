<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 30.04.15
 * Time: 16:50
 */

namespace modules\contract\controllers;


use modules\contract\models\Contract;
use modules\contract\models\form\OrderCommentForm;
use modules\contract\models\Order;
use modules\contract\models\OrderComment;
use modules\site\components\Controller;
use yii\bootstrap\ActiveForm;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\web\Response;

class CommentOrderController extends Controller
{

    public function actionIndex($orderId){
        $order = Order::findOne($orderId);
        $comments = $order ? $order->orderComments : [];
        echo $this->renderPartial('index', [
            'comments' => $comments,
            'order' => $order,
        ]);
    }

    public function actionList($orderId){
        $order = Order::findOne($orderId);
        $comments = $order ? $order->orderComments : [];
        echo $this->renderPartial('_list', [
            'comments' => $comments,
        ]);
    }

    public function actionCreate(){
        $curContract = Contract::getCurContract();
        $model = new OrderCommentForm();
        $post = \Yii::$app->getRequest()->post();
        if($model->load($post)){
            $order = Order::findOne($model->order_id);
            if($model->validate()){
                $comment = new OrderComment();
                $comment->setAttributes($model->attributes);
                $comment->contract_id = $curContract->id;
                $comment->order_id = $order->id;
                if($comment->save()){
                    return Json::encode(['output'=>'success']);
                }
            }
            else{
                \Yii::$app->response->setStatusCode(400);
                return Json::encode(['output'=>'error', 'error' => ActiveForm::validate($model)]);
            }
        }
        else{
            $order = new Order();
        }
        echo $this->renderAjax('_form', [
            'order' => $order,
        ]);
    }

} 