<?php
return [
//    'class'=>\frontend\components\LangUrlManager::className(),
    'rules' => [
        '<controller:\w+>/<action:\w+>/*'=>'<controller>/<action>',
    ],
];