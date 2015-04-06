<?php

namespace modules\site;

use modules\translations\components\DbMessageSource;
use Yii;

/**
 * Main frontend module.
 */
class Module extends \yii\base\Module
{

    public function init()
    {
        $app = \Yii::$app;
        if (!isset($app->i18n->translations['site'])) {
            $app->i18n->translations['site'] = [
                'class' => DbMessageSource::className(),
                'forceTranslation' => true,
            ];
        }

        parent::init();
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t($category, $message, $params, $language);
    }
}
