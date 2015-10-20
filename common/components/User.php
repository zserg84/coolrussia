<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 24.04.15
 * Time: 15:43
 */

namespace common\components;

class User extends \yii\web\User
{

    private $_role;

    public function getRole(){
        if(\Yii::$app->getUser()->getIsGuest())
            return false;
        $user = \modules\users\models\User::findOne(\Yii::$app->getUser()->id);
        return $user->role;
    }
} 