<?php

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'console\controllers',
    'bootstrap' => [
        'log'
    ],
    'modules' => [
        'rbac' => [
            'class' => modules\rbac\Module::className(),
            'controllerNamespace' => 'modules\rbac\commands',
        ],
        'users' => [
            'class' => 'vova07\users\Module',
            'controllerNamespace' => 'vova07\users\commands'
        ],
        'blogs' => [
            'class' => 'vova07\blogs\Module',
            'controllerNamespace' => 'vova07\blogs\commands'
        ],
        'comments' => [
            'class' => 'vova07\comments\Module',
            'controllerNamespace' => 'vova07\comments\commands'
        ],
        'translations' => [
            'class' => modules\translations\Module::className(),
            'controllerNamespace' => 'modules\translations\controllers\console',
        ],
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning']
                ]
            ]
        ]
    ],
    'params' => require(__DIR__ . '/params.php')
];
