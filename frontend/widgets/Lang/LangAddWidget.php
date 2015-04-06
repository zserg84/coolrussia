<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 03.03.15
 * Time: 15:48
 */

namespace frontend\widgets\Lang;

use modules\lang\models\Lang;
use yii\bootstrap\Widget;

class LangAddWidget extends Widget
{

    public $view;

    public function init(){

    }

    public function run(){
        $curLanguage = Lang::getCurrent();

        return $this->render('add', [
            'curLanguage' => $curLanguage,
            'view' => $this->view,
        ]);
    }
}