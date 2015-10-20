<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use modules\translations\controllers\console\I18nController;


/**
 * Console
 */
class MakeController extends Controller
{

    /**
     * @usage php yii make/all
     */
    public function actionAll()
    {
        $modules = ['themes', 'image', 'translations', 'users', 'lang', 'page', 'rbac', 'site'];
        foreach($modules as $moduleName){
            $this->_translate('modules/'.$moduleName.'/messages');
        }

        return 0;
    }

    private function _translate($path){
        $done = $this->ansiFormat('DONE', Console::BG_GREEN);
        $name = $this->ansiFormat($path, Console::BG_BLACK);
        echo "\n$name ...";
        $var = new I18nController('i18n', Yii::$app->module);
        $var->actionImport($path, false);
        echo "$done";

        echo "\n";
    }
}