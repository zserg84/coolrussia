<?php

namespace modules\themes\site;

use Yii;

/**
 * Class Theme
 * @package vova07\themes\admin
 */
class Theme extends \yii\base\Theme
{
    /**
     * @inheritdoc
     */
    public $pathMap = [
        '@frontend/views' => '@modules/themes/site/views',
        '@frontend/modules' => '@modules/themes/site/modules',
        '@customer/views' => '@modules/themes/site/views',
        '@customer/modules' => '@modules/themes/site/modules'
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        Yii::$app->assetManager->bundles['yii\bootstrap\BootstrapAsset'] = [
            'sourcePath' => '@modules/themes/site/assets',
            'css' => [
                'css/bootstrap.min.css'
            ]
        ];
        Yii::$app->assetManager->bundles['yii\bootstrap\BootstrapPluginAsset'] = [
            'sourcePath' => '@modules/themes/site/assets',
            'js' => [
                'js/bootstrap.min.js',
            ]
        ];
    }
}
