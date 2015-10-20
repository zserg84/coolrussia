<?php

namespace customer\components;

use yii\filters\AccessControl;

trait CustomerTrait
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['customer']
                    ]
                ]
            ]
        ];
    }
}
