<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 24.04.15
 * Time: 11:44
 */

namespace modules\contract\widgets\filter;

use yii\bootstrap\Widget;

/*
 * Виджет для фильтрации списков
 * */
class FilterWidget extends Widget
{

    public $id;

    /*
     * Url, куда отправляем форму для фильтрации
     * */
    public $actionUrl;

    /*
     * [name, filterView]
     * */
    public $filterParams = [];

    public function run(){
        return $this->render('main', [
            'filterParams' => $this->filterParams,
            'id' => $this->id,
            'action' => $this->actionUrl,
        ]);
    }

} 