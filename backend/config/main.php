<?php

Yii::setAlias('backend', dirname(__DIR__));

return [
    'id' => 'app-backend',
    'name' => 'CoolRussia',
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'admin/default/index',
    'modules' => [
        'admin' => [
            'class' => 'vova07\admin\Module'
        ],
        'users' => [
            'controllerNamespace' => 'modules\users\controllers\backend',
        ],
        'lang' => [
            'controllerNamespace' => 'modules\lang\controllers\backend',
            'isBackend' => true,
        ],
        'blogs' => [
            'controllerNamespace' => 'modules\blog\controllers\backend'
        ],
        'comments' => [
            'isBackend' => true
        ],
        'rbac' => [
            'class' => 'vova07\rbac\Module',
            'isBackend' => true
        ],
        'translations' => [
            'class' => modules\translations\Module::className(),
            'controllerNamespace' => 'modules\translations\controllers\backend',
            'isBackend' => true,
        ],
        'faq' => [
            'controllerNamespace' => 'modules\faq\controllers\backend',
            'isBackend' => true,
        ],
        'page' => [
            'controllerNamespace' => 'modules\page\controllers\backend',
            'isBackend' => true,
        ],
        'geo' => [
            'controllerNamespace' => 'modules\geo\controllers\backend',
            'isBackend' => true,
        ],
    ],
    'components' => [
        'request' => [
            'class' => 'frontend\components\LangRequest',
            'cookieValidationKey' => '7fdsf%dbYd&djsb#sn0mlsfo(kj^kf98dfh',
            'baseUrl' => '/backend'
        ],
        'urlManager' => [
            'rules' => [
                '' => 'admin/default/index',
                '<_m>/<_c>/<_a>' => '<_m>/<_c>/<_a>',
            ]
        ],
        'view' => [
            'theme' => 'modules\themes\admin\Theme'
        ],
        'errorHandler' => [
            'errorAction' => 'admin/default/error'
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning']
                ]
            ]
        ],
//        'i18n' => [
//            'translations' => [
//                '*' => [
//                    'class' => 'yii\i18n\PhpMessageSource',
//                    'basePath' => '@modules/themes/admin/messages',
//                    'sourceLanguage' => 'en',
//                    'fileMap' => [
//                        'admin' => 'admin.php',
//                        'widgets/box' => 'box.php'
//                    ],
//                ],
//            ],
//        ],
    ],
    'params' => require(__DIR__ . '/params.php')
];
