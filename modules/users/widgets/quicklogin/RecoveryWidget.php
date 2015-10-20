<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 08.05.15
 * Time: 15:57
 */

namespace modules\users\widgets\quicklogin;

use \yii\bootstrap\Widget;

class RecoveryWidget extends Widget
{
    public function init(){
        $this->getView()->registerJs('
            $(document).on("click", ".recovery", function(){
                $(".modal").modal("hide");
                $("#recovery-modal").modal();
                return false;
            });
        ');
    }

    public function run(){
        return \Yii::$app->controller->run('/users/guest/recovery');
    }
} 