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

class CommentWidget extends Widget
{
    public $jsOptions = [];

    public $contract;

    public function init(){
        if(!$this->contract)
            throw new Exception('Contract was not set');

        $this->registerClientScript();
    }

    public function run(){
        return \Yii::$app->controller->run('comment/index', [
            'contractId' => $this->contract->id,
        ]);
    }

    public function registerClientScript(){
        $view = $this->getView();
        $options = Json::encode($this->jsOptions);
        Asset::register($view);
        $view->registerJs('jQuery.comments(' . $options . ');');

    }

} 