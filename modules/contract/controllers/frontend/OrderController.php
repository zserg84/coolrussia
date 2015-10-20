<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 24.04.15
 * Time: 10:29
 */

namespace modules\contract\controllers\frontend;

use modules\contract\widgets\actionButtons\orders\CustomerProfileButton;
use modules\contract\widgets\actionButtons\orders\OrderLinkButton;

class OrderController extends \modules\contract\controllers\OrderController
{

    public function actionList(){
        $this->_buttons = [
            OrderLinkButton::className(),
            CustomerProfileButton::className(),
        ];
        return parent::actionList();
    }
} 