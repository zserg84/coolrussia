<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 23.04.15
 * Time: 13:02
 */

namespace modules\users\widgets\quicklogin;

use yii\bootstrap\Widget;

class ModalLoginWidget extends Widget
{

    public function run(){
        \Yii::$app->controller->run('/users/guest/modallogin');
    }
} 