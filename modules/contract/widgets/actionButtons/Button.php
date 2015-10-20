<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 26.05.15
 * Time: 11:01
 */

namespace modules\contract\widgets\actionButtons;

use yii\bootstrap\Widget;
use yii\web\View;


/*
 * Кнопки действий для списков (списка заказов, списка исполнителей)
 * */
class Button extends Widget
{

    /*
     * Кнопка(ссылка)
     * */
    public $button;

    /*
     * js-обработчик кнопки
     * */
    public $jsHandler;

    /*
     * Модель заказа (исполнителя или другой сущности, для которой рисуем кнопку)
     * */
    public $model;

    /*
     * id pjax-контейнера.
     * */
    public $pjaxContainerId;

    public function init(){
        $this->registerClientScript();
    }

    public function run(){
        if($this->button){
            return $this->button;
        }
    }

    public function registerClientScript(){
        $view = $this->getView();

        if($this->jsHandler)
            $view->registerJS($this->jsHandler);
    }
} 