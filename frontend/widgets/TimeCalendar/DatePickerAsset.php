<?php

namespace frontend\widgets\TimeCalendar;

use yii\web\AssetBundle;

class DatePickerAsset extends AssetBundle
{

    public $css = [
        'css/main.css',
    ];
    public $js = [
        'js/main.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public $jsOptions = array(
        'position' => \yii\web\View::POS_END
    );

    public function init(){
        $this->sourcePath = __DIR__ . '/assets';
    }
}
