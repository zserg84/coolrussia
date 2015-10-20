<?php

namespace modules\themes\tender;

use Yii;

/**
 * Class Theme
 */
class Theme extends \yii\base\Theme
{
    /**
     * @inheritdoc
     */
    public $pathMap = [
        '@frontend/views' => '@modules/themes/tender/views',
        '@frontend/modules' => '@modules/themes/tender/modules',
        '@customer/views' => '@modules/themes/tender/views',
        '@customer/modules' => '@modules/themes/tender/modules',
        '@performer/views' => '@modules/themes/tender/views',
        '@performer/modules' => '@modules/themes/tender/modules'
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        Yii::$app->assetManager->bundles['yii\bootstrap\BootstrapAsset'] = [
            'sourcePath' => '@modules/themes/tender/assets',
            'css' => [
                'css/bootstrap.css',
                'css/normalize.css',
                'css/dd-select.css',
                'css/datepicker.css',
                'css/jquery.mCustomScrollbar.css',
                'css/style.css',
            ],
            'cssOptions' => ['position' => \yii\web\View::POS_END]
        ];
        Yii::$app->assetManager->bundles['yii\bootstrap\BootstrapPluginAsset'] = [
            'sourcePath' => '@modules/themes/tender/assets',
            'js' => [
                'js/bootstrap.min.js',
                'js/jquery.datetimepicker.js',
                'js/main.js',
                'js/less.js',
            ],
            'jsOptions' => ['position' => \yii\web\View::POS_HEAD]
        ];
    }
}
