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
        $langNames = ['themes-admin', 'Horizontal menu ALL pages', 'Homepage', 'Login form for ALL pages', 'ALL_INTERFACES', 'GUEST_INTERFACE', 'CUSTOMER_INTERFACE', 'PERFORMER_INTERFACE',
        'FORM_ORDER', 'FORM_PERFORMER_SETTINGS', 'FORM_CUSTOMER_SETTINGS'];
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