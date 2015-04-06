<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 02.02.15
 * Time: 10:36
 */
namespace frontend\widgets\Lang;

use modules\lang\models\Lang;
use yii\bootstrap\Widget;

class LangWidget extends Widget
{
    public function init(){}

    public function run() {
        return $this->render('view', [
            'current' => Lang::getCurrent(),
            'langs' => Lang::find()->where('id != :current_id', [':current_id' => Lang::getCurrent()->id])->all(),
        ]);
    }
}