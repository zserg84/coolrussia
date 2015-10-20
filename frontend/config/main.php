<?php

return [
    'id' => 'app-frontend',
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
            'controllerNamespace' => 'modules\users\controllers\frontend',
        ],
        'page' => [
            'controllerNamespace' => 'modules\page\controllers\frontend',
        ],
        'contract' => [
            'controllerNamespace' => 'modules\contract\controllers\frontend',
        ],
    ],
    'components' => [
        'request' => [
            'class' => 'frontend\components\LangRequest',
            'cookieValidationKey' => 'sdi8s#fnj98jwiqiw;qfh!fjgh0d8f',
            'baseUrl' => ''
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
                    'basePath' => '@modules/themes/tender/messages',
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
