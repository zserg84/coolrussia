<?php

namespace modules\themes;

use modules\translations\components\DbMessageSource;
use Yii;


class Module extends \yii\base\Module {

    public function init()
    {
        self::initLang();

        parent::init();
    }

    public static function  initLang(){
        $langNames = ['themes-site', 'themes-admin'];
        $app = \Yii::$app;
        foreach($langNames as $langName){
            if (!isset($app->i18n->translations[$langName])) {
                $app->i18n->translations[$langName] = [
                    'class' => DbMessageSource::className(),
                    'forceTranslation' => true,
                ];
            }
        }
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        self::initLang();
        return Yii::t($category, $message, $params, $language);
    }

}