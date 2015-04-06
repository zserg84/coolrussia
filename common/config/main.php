<?php

return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'timeZone' => 'Europe/Moscow',
    'modules' => [
        'users' => [
            'class' => modules\users\Module::className(),
            'robotEmail' => 'robot@coolrussia.ru',
            'robotName' => 'CoolRussia',
            'requireEmailConfirmation'=>false,
        ],
        'geo' => [
            'class' => modules\geo\Module::className(),
        ],
        'image' => [
            'class' => modules\image\Module::className(),
            'path' => Yii::getAlias('@frontend').'/web/img/',
            'url' => '/img/',
            'sizeArray' => [100, 200, 500, 1000],
        ],
        'lang' => [
            'class' => modules\lang\Module::className(),
        ],
        'blogs' => [
            'class' => modules\blog\Module::className(),
        ],
        'comments' => [
            'class' => modules\comments\Module::className(),
        ],
        'gridview' => [
            'class' => \kartik\grid\Module::className(),
        ],
        'translations' => [
            'class' => modules\translations\Module::className(),
        ],
        'faq' => [
            'class' => modules\faq\Module::className(),
        ],
        'event' => [
            'class' => modules\event\Module::className(),
        ],
        'page' => [
            'class' => modules\page\Module::className(),
        ],
    ],
    'components' => [
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'modules\users\models\User',
            'loginUrl' => ['/users/guest/login'],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@root/cache',
            'keyPrefix' => 'yii2start',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'suffix' => '/',
        ],
        'authManager' => [
            /*/
            'class' => 'yii\rbac\PhpManager',
            'itemFile' => '@vova07/rbac/data/items.php',
            'assignmentFile' => '@vova07/rbac/data/assignments.php',
            'ruleFile' => '@vova07/rbac/data/rules.php',
            /*/
            'class' => 'yii\rbac\DbManager',
            'itemTable' => '{{%auth_item}}',
            'itemChildTable' => '{{%auth_item_child}}',
            'assignmentTable' => '{{%auth_assignment}}',
            'ruleTable' => '{{%auth_rule}}',
            /**/
            'defaultRoles' => [
                'user',
            ],
        ],
        'formatter' => [
            'dateFormat' => 'dd.MM.y',
            'datetimeFormat' => 'HH:mm:ss dd.MM.y',
        ],
        'db' => require(__DIR__ . '/db.php'),
        'language'=>'ru-RU',
        'i18n' => [
            'class' => modules\translations\components\I18N::className(),
        ],
        'mailer' => [
            'class' => \yii\swiftmailer\Mailer::className(),
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'constructArgs' => ['localhost', 25],
            ],
        ],
    ],
    'params' => require(__DIR__ . '/params.php'),
];
