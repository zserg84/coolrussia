<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 07.05.15
 * Time: 16:48
 */

namespace modules\contract\controllers\customer;

use modules\contract\models\form\customer\SettingsForm;

class SettingsController extends \modules\contract\controllers\SettingsController
{
    protected function getSettingsForm(){
        return new SettingsForm();
    }
} 