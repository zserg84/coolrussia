<?php

namespace modules\themes\tender;

use yii\web\AssetBundle;

/**
 * Theme main asset bundle.
 */
class ThemeAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@modules/themes/tender/assets';

    /**
     * @inheritdoc
     */
    public $css = [];

    public $js = [];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'common\components\BootboxAsset',
    ];

    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];

    public static function imgSrc($relativePath,$assetImageDir = 'img' ){
        $obj = new self();
        return \Yii::$app->assetManager->getPublishedUrl($obj->sourcePath)
        . "/" . $assetImageDir . "/" . $relativePath;
    }
}
