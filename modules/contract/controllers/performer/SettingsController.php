<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 07.05.15
 * Time: 16:48
 */

namespace modules\contract\controllers\performer;

use modules\contract\models\form\performer\SettingsForm;

class SettingsController extends \modules\contract\controllers\SettingsController
{
    protected function getSettingsForm(){
        return new SettingsForm();
    }
}