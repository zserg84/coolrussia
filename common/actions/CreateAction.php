<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 04.02.15
 * Time: 14:39
 */

namespace common\actions;

use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;

class CreateAction extends Action {

    public $view = 'create';

    public $ajaxValidation = false;

    public function run(){

        $model = $this->getModel();

        if($beforeAction = $this->beforeAction){
            $model = call_user_func($beforeAction, $this, $model);
        }

        if($post = \Yii::$app->getRequest()->post()){
            $this->loadData($model, $post);
            if (Yii::$app->request->isAjax and $this->ajaxValidation) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            if($model->save()){
                if($afterAction = $this->afterAction){
                    $model = call_user_func($afterAction, $model);
                }
                if($this->accessMsg) {
                    \Yii::$app->session->setFlash('message', $this->accessMsg);
                }
                if($this->redirectUrl){
                    $this->controller->redirect($this->redirectUrl);
                }
            }
            else{
                if($this->errorMsg){
                    \Yii::$app->session->setFlash('message', $this->errorMsg);
                }
            }

            if(\Yii::$app->getRequest()->isAjax){ // or isPjax
                $viewParams = array_merge(['model'=>$model], $this->viewParams);
                $viewParams['viewParams'] = $viewParams;
                return $this->controller->renderAjax($this->view, $viewParams);
            }
            \Yii::$app->end();
        }

        $viewParams = array_merge(['model'=>$model], $this->viewParams);
        $viewParams['viewParams'] = $viewParams;
        return $this->controller->render($this->view, $viewParams);
    }

    public function getModel(){
        return new $this->modelClass;
    }

    public function loadData($model, $post){
        $model->load($post);
    }
} 