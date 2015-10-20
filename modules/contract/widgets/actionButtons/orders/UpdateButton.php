<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 27.05.15
 * Time: 15:28
 */

namespace modules\contract\widgets\actionButtons\orders;

use modules\contract\Module as ContractModule;
use modules\contract\widgets\actionButtons\Button;
use yii\helpers\Html;
use yii\helpers\Url;

class UpdateButton extends Button
{

    public function init()
    {
        $this->button = Html::a(ContractModule::t('GUEST_INTERFACE', 'VIEW_ELEMENT_TAPE_OF_ORDERS_BUTTON_CUSTOMER_PROFILE'), "javascript:void(0)", [
            'class' => 'order_update_link'
        ]);

        $this->jsHandler = '
            $(".order_update_link").click(function() {
                var url = "' . Url::toRoute(['update']) . '";
                $.pjax({url: url, container: "#'.$this->pjaxContainerId.'", data:{orderId: $(this).closest("tr").data("order")}, push:false, replace:false, type:"post"});
            });

            $("#'.$this->pjaxContainerId.'").on("pjax:end", function() {
                initPage();
                initPopup();
                $("#order-update-modal").modal();
            });
        ';

        parent::init();
    }
} 