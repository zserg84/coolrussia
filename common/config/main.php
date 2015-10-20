<?php

return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'timeZone' => 'Europe/Moscow',
    'name' => 'TenderSystem',
    'modules' => [
        'users' => [
            'class' => modules\users\Module::className(),
            'robotEmail' => 'registration@glancecom.ru',
            'robotName' => 'TenderRobot',
            'requireEmailConfirmation'=>true,
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
        'gridview' => [
            'class' => \kartik\grid\Module::className(),
        ],
        'page' => [
            'class' => modules\page\Module::className(),
        ],
        'translations' => [
            'class' => modules\translations\Module::className(),
        ],
        'contract' => [
            'class' => modules\contract\Module::className(),
        ],
        'direction' => [
            'class' => modules\direction\Module::className(),
        ],
    ],
    'components' => [
        'user' => [
            'class' => \common\components\User::className(),
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
                'user', 'customer', 'performer',
            ],
        ],
        'assetManager' => [
            'class'=>'yii\web\AssetManager',
            'linkAssets' => true,
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
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'mail.tagtech.ru',
                'username' => 'tag311706',
                'password' => '123456',
                'port' => '25',
//                'encryption' => 'tls',
            ],
        ],
    ],
    'params' => require(__DIR__ . '/params.php'),
];
