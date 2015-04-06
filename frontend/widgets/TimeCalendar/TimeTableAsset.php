<?php

namespace frontend\widgets\TimeCalendar;

use yii\web\AssetBundle;

class TimeTableAsset extends AssetBundle
{

    public $css = [
        'css/main.css',
    ];
    public $js = [
        'js/timetable.js',
    ];
    public $depends = [
        'frontend\widgets\TimeCalendar\DatePickerAsset',
    ];

    public $jsOptions = array(
        'position' => \yii\web\View::POS_END
    );

    public function init(){
        $this->sourcePath = __DIR__ . '/assets';
    }
}
