<?php

return [
    'id' => 'app-frontend',
    'name' => 'Cool Russia',
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
        'geo'=> [
            'controllerNamespace' => 'modules\geo\controllers',
        ],
        'blogs' => [
            'controllerNamespace' => 'modules\blog\controllers\frontend',
        ],
        'event' => [
            'controllerNamespace' => 'modules\event\controllers\frontend',
        ],
        'page' => [
            'controllerNamespace' => 'modules\page\controllers\frontend',
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
            'theme' => 'modules\themes\site\Theme'
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
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'vkontakte' => [
                    'class' => 'yii\authclient\clients\VKontakte',
                    'clientId' => '4764802',
                    'clientSecret' => 'nglq5aHX2we0ZEE3lbU3',
                    'scope' => 'email',
                    'viewOptions' => [
                        'popupWidth' => '656px',
                        'popupHeight' => '378px',
                    ],
                ],
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'authUrl' => 'https://www.facebook.com/dialog/oauth?display=popup',
                    'clientId' => '1597966723772433',
                    'clientSecret' => '4363cde24f26a2c53934a8d979c7bd1a',
                    'scope'=>'email',
                    'viewOptions' => [
                        'popupWidth' => '600px',
                        'popupHeight' => '320px',
                    ],
                ],
                'twitter' => [
                    'class' => 'yii\authclient\clients\Twitter',
                    'consumerKey' => 'p17PWzViwHtbThBJA0e5P8YpG',
                    'consumerSecret' => 'tlcLKnZek5JVnEZp5JYghoYzlCCh2Z6wx12FYRAfdKzhIOTlGw',
                    'requestTokenMethod' => 'GET',
                    'accessTokenMethod' => 'GET',
                    'authUrl' => 'https://api.twitter.com/oauth/authenticate',
                    'requestTokenUrl' => 'https://api.twitter.com/oauth/request_token',
                    'accessTokenUrl' => 'https://api.twitter.com/oauth/access_token',
                    'scope'=>'email',
                ],
            ],
        ]
    ],
    'params' => require(__DIR__ . '/params.php')
];
