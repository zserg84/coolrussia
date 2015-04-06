<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 04.02.15
 * Time: 15:48
 */

namespace common\actions;


class DeleteAction extends Action{

    public function run(){
        $model = $this->getModel();

        if ($model->delete() === false) {
            throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
        }

        \Yii::$app->getResponse()->setStatusCode(204);
    }

    public function loadData($model, $post){

    }
} 