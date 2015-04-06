<?php

namespace modules\themes\admin;

use yii\web\AssetBundle;

/**
 * Theme main asset bundle.
 */
class ThemeAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@modules/themes/admin/assets';

    /**
     * @inheritdoc
     */
    public $css = [
//        'css/font-awesome.min.css',
        'awesome'=>'//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
        'css/ionicons.min.css',
        'css/AdminLTE.css',
        'css/custom.css'
    ];

    public $js = [
        'js/AdminLTE/app.js',
        'js/pjax.js',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset'
    ];

    public $jsOptions = array(
        'position' => \yii\web\View::POS_HEAD
    );
}
