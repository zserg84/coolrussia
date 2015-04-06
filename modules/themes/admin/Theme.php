<?php

namespace modules\themes\admin;

use Yii;

/**
 * Class Theme
 * @package modules\themes\admin
 */
class Theme extends \yii\base\Theme
{
    /**
     * @inheritdoc
     */
    public $pathMap = [
        '@backend/views' => '@modules/themes/admin/views',
        '@backend/modules' => '@modules/themes/admin/modules'
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        Yii::$app->assetManager->bundles['yii\bootstrap\BootstrapAsset'] = [
            'sourcePath' => '@modules/themes/admin/assets',
            'css' => [
                'css/bootstrap.min.css'
            ]
        ];
        Yii::$app->assetManager->bundles['yii\bootstrap\BootstrapPluginAsset'] = [
            'sourcePath' => '@modules/themes/admin/assets',
            'js' => [
                'js/bootstrap.min.js'
            ]
        ];
        Yii::$container->set('yii\grid\CheckboxColumn', [
            'checkboxOptions' => [
                'class' => 'simple'
            ]
        ]);
    }
}
