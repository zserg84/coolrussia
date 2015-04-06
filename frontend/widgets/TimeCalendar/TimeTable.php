<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 02.02.15
 * Time: 10:36
 */
namespace frontend\widgets\TimeCalendar;

use modules\event\models\EventTimeRepeat;
use modules\event\Module;
use yii\base\Exception;
use yii\bootstrap\Widget;

class TimeTable extends Widget
{
    public $model;

    public $attribute;

    public $date;

    public $form;

    public function init(){
        $this->registerAssets();
    }

    public function run() {
        echo $this->render('table', [
            'weekdays' => EventTimeRepeat::getWeekDays(),
        ]);
    }

    public function registerAssets()
    {
        TimeTableAsset::register($this->getView());
    }
}