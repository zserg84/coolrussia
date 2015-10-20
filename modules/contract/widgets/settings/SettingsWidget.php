<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 07.05.15
 * Time: 16:47
 */

namespace modules\contract\widgets\settings;

use yii\bootstrap\Widget;

class SettingsWidget extends Widget
{

    public function run(){
        return \Yii::$app->controller->run('settings/update');
    }
} 