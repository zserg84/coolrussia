<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 28.04.15
 * Time: 12:31
 */

namespace modules\contract\controllers;

use modules\contract\models\Contract;
use modules\contract\models\ContractComment;
use modules\contract\models\form\CommentForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use \yii\web\Controller;
use yii\web\Response;

class CommentController extends Controller
{

    public function actionIndex($contractId){
        $contract = Contract::findOne($contractId);
        $comments = $contract ? $contract->contractComments : [];
        return $this->renderPartial('index', [
            'comments' => $comments,
            'contract' => $contract,
        ]);
    }

    public function actionList($contractId){
        $contract = Contract::findOne($contractId);
        $comments = $contract ? $contract->contractComments : [];
        return $this->renderPartial('_list', [
            'comments' => $comments,
        ]);
    }

    public function actionCreate(){
        $curContract = Contract::getCurContract();
        $model = new CommentForm();
        $post = \Yii::$app->getRequest()->post();
//        $this->performAjaxValidation($model);
        if($model->load($post)){
            $contract = Contract::findOne($model->contract_id);
            if($model->validate()){
                $comment = new ContractComment();
                $comment->setAttributes($model->attributes);
                $comment->comment_contract_id = $curContract->id;
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
            $contract = new Contract();
        }
        echo $this->renderAjax('_form', [
            'contract' => $contract,
        ]);
    }

    protected function performAjaxValidation($model)
    {
        if (\Yii::$app->request->isAjax && $model->load(\Yii::$app->request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $model->setScenario('ajax');
            return ActiveForm::validate($model);
        }
    }
} 