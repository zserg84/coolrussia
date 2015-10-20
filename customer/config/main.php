<?php

return [
    'id' => 'app-customer',
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'site/default/index',
    'modules' => [
        'site' => [
            'class' => 'modules\site\Module',
        ],
        'themes' => [
            'class' => 'modules\themes\Module',
        ],
        'users'=> [
            'controllerNamespace' => 'modules\users\controllers\customer',
            'interfaceType' => 'customer',
        ],
        'page' => [
            'controllerNamespace' => 'modules\page\controllers\customer',
        ],
        'contract' => [
            'controllerNamespace' => 'modules\contract\controllers\customer',
            'interfaceType' => 'customer',
        ],
    ],
    'components' => [
        'request' => [
            'class' => 'frontend\components\LangRequest',
            'cookieValidationKey' => 'sdi8s#fnj98jwiqiw;qfh!fjgh0d8f',
            'baseUrl' => '/customer'
        ],
        'urlManager' => require(__DIR__ . '/urlManager.php'),
        'view' => [
            'theme' => 'modules\themes\tender\Theme'
        ],
        'errorHandler' => [
            'errorAction' => 'site/default/error'
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
        'language'=>'ru-RU',
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@modules/themes/site/messages',
                    'sourceLanguage' => 'en',
                    'fileMap' => [
                        'site' => 'site.php',
                    ],
                ],
            ],
        ],
    ],
    'params' => require(__DIR__ . '/params.php')
];
