<?php

namespace performer\components;

use yii\filters\AccessControl;

trait PerformerTrait
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['performer']
                    ]
                ]
            ]
        ];
    }
}