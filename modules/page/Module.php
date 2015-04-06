<?php

namespace modules\page;

use Yii;
use modules\translations\components\DbMessageSource;

class Module extends \modules\base\components\Module
{
    public static $name = 'page';

    public $link = '/page/';


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $app = Yii::$app;
        if (!isset($app->i18n->translations['page'])) {
            $app->i18n->translations['page'] = [
                'class' => DbMessageSource::className(),
                'forceTranslation' => true,
            ];
        }
    }


    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t($category, $message, $params, $language);
    }
}