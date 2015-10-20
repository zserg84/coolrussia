<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 23.04.15
 * Time: 13:42
 */
namespace modules\users\widgets\userinfo;

use modules\users\models\backend\User;

class UserinfoWidget extends  \yii\bootstrap\Widget
{

    public function run(){
        $userId = \Yii::$app->getUser()->getId();
        if($user = User::findOne($userId)){
            echo $this->render('userinfo', [
                'user' => $user,
            ]);
        }
    }
} 