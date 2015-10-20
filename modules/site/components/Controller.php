<?php

namespace modules\site\components;

use Yii;
use yii\bootstrap\ActiveForm;
use yii\helpers\Json;
use yii\web\Response;

/**
 * Main frontend controller.
 */
class Controller extends \yii\web\Controller
{
    protected function performAjaxValidation($model)
    {
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model->setScenario('ajax');
            if(ActiveForm::validate($model))
                return Json::encode(['output'=>'error', 'error' => ActiveForm::validate($model)]);
//            \Yii::$app->end();
        }
    }
}
