<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 30.04.15
 * Time: 11:59
 */

namespace modules\contract\widgets\order;

use yii\bootstrap\Widget;
use yii\helpers\Html;
use modules\themes\Module as ThemeModule;
use yii\helpers\Url;

class OrderCreateWidget extends Widget
{
    public function run(){

        echo '<div id="pjax-order-create-container"></div>';
        echo Html::a(ThemeModule::t('ALL_INTERFACES', 'NEW_ORDER_BUTTON'), 'javascript:void(0)', [
            'class' => 'create-order open-modal',
            'onclick' => '
                $("#order-create-modal").modal("show");
            ',
        ]);

        $view = $this->getView();
        $view->registerJS('
            $(".create-order").click(function() {
                var url = "' . Url::toRoute(['/contract/order/create']) . '";
                $.pjax({url: url, container: "#pjax-order-create-container", push:false, replace:false, type:"post"});
            });

            $("#pjax-order-create-container").on("pjax:end", function() {
                initPage();
                initPopup();
                $("#order-create-modal").modal();
            });
        ');
//        return \Yii::$app->controller->run('order/create');
    }
} 