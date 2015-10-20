<?php

Yii::setAlias('backend', dirname(__DIR__));

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'admin/default/index',
    'modules' => [
        'admin' => [
            'class' => 'vova07\admin\Module'
        ],
        'users' => [
            'controllerNamespace' => 'modules\users\controllers\backend',
            'interfaceType' => 'backend',
        ],
        'lang' => [
            'controllerNamespace' => 'modules\lang\controllers\backend',
            'interfaceType' => 'backend',
        ],
        'rbac' => [
            'class' => 'modules\rbac\Module',
            'interfaceType' => 'backend',
        ],
        'page' => [
            'controllerNamespace' => 'modules\page\controllers\backend',
            'interfaceType' => 'backend',
        ],
        'translations' => [
            'class' => modules\translations\Module::className(),
            'controllerNamespace' => 'modules\translations\controllers\backend',
            'interfaceType' => 'backend',
        ],
        'direction' => [
            'controllerNamespace' => 'modules\direction\controllers\backend',
            'interfaceType' => 'backend',
        ],
        'exchange' => [
            'class' => 'modules\exchange\Module',
            'controllerNamespace' => 'modules\exchange\controllers\backend',
            'interfaceType' => 'backend',
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
