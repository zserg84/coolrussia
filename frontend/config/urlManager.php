<?php
return [
//    'class'=>\frontend\components\LangUrlManager::className(),
    'rules' => [
        '' => 'site/default/index',
        '<_a:(agreement|fororg|about|feedback|captcha)>' => 'site/default/<_a>',
        'user/<id:\d+>' => '/users/default/view',
        'profile' => '/users/default/view',
        'faq' => '/faq/default/index',
        'events/edit/<id:\d+>' => '/event/default/edit',
        'events/create' => '/event/default/edit',
        'events' => '/event/default/index',
        'events/<id:\d+>' => '/event/default/view',
        'page/<url:\w+>' => 'page/default/view',
        'img/<group:\d+>/<id:\d+>-<width:\d+>.<ext:(jpg|jpeg|gif|png)>' => '/image/default/index',
        '<controller:\w+>/<action:\w+>/*'=>'<controller>/<action>',
    ],
];