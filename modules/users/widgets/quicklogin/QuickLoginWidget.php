<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 23.04.15
 * Time: 13:02
 */

namespace modules\users\widgets\quicklogin;

use modules\users\models\LoginForm;

class QuickLoginWidget extends \yii\bootstrap\Widget
{

    public function run(){
        \Yii::$app->controller->run('/users/guest/quicklogin');
    }
} 