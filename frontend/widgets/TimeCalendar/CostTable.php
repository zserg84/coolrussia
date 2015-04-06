<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 18.02.15
 * Time: 12:45
 */

namespace frontend\widgets\TimeCalendar;

use yii\bootstrap\Widget;
use yii\helpers\VarDumper;

class CostTable extends Widget
{
    public $model;

    public $attribute;

    public $values = [];

    public function init(){
        $this->registerAssets();
    }

    public function run() {
        $model = $this->model;
        $attribute = $this->attribute;
        $values = $model->$attribute;
        echo $this->render('cost_table', [
            'values'=>$values,
        ]);
    }

    public function registerAssets()
    {
        CostTableAsset::register($this->getView());
    }
}