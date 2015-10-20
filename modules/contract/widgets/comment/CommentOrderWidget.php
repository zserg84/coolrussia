<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 24.04.15
 * Time: 11:44
 */

namespace modules\contract\widgets\comment;

use yii\base\Exception;
use yii\bootstrap\Widget;
use yii\helpers\Json;

class CommentOrderWidget extends Widget
{
    public $jsOptions = [];

    public $order;

    public function init(){
        if(!$this->order)
            throw new Exception('Order was not set');

        $this->registerClientScript();
    }

    public function run(){
        return \Yii::$app->controller->run('comment-order/index', [
            'orderId' => $this->order->id,
        ]);
    }

    public function registerClientScript(){
        $view = $this->getView();
        $options = Json::encode($this->jsOptions);
        Asset::register($view);
        $view->registerJs('jQuery.comments(' . $options . ');');

    }

} 