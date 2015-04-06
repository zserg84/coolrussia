<?php

namespace modules\image;

use Yii;

class Module extends \yii\base\Module {

    public $controllerNamespace = 'modules\image\controllers';

    // save
    public $path = '/';

    // show
    public $url = '/';

    public $sizeArray = [];


    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/' . $category, $message, $params, $language);
    }

}