<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 05.05.15
 * Time: 11:39
 */

namespace modules\contract\controllers;

use modules\contract\models\Contract;
use modules\contract\models\Order;
use modules\site\components\Controller;
use yii\helpers\VarDumper;

class CustomerController extends Controller
{

    public function actionProfile($contractId){
        $model = Contract::findOne($contractId);
        echo $this->renderAjax('_profile_view', [
            'model' => $model,
        ]);
    }
} 