<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 18.02.15
 * Time: 12:46
 */

namespace frontend\widgets\TimeCalendar;


use yii\web\AssetBundle;

class CostTableAsset extends AssetBundle {

    public $js = [
        'js/costtable.js',
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