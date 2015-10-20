<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 26.05.15
 * Time: 11:08
 */

namespace modules\contract\widgets\actionButtons\orders;

use modules\contract\Module as ContractModule;
use modules\contract\widgets\actionButtons\Button;
use yii\helpers\Html;
use yii\helpers\Url;

/*
 * Кнопка "Просмотр заказа"
 * */
class OrderLinkButton extends Button
{

    public function init()
    {
        if(\Yii::$app->getUser()->isGuest){
            $onlyReg = 'Просмотр информации возможен только зарегистрированными пользователями';
            $this->button = Html::a(ContractModule::t('GUEST_INTERFACE', 'VIEW_ELEMENT_TAPE_OF_ORDERS_BUTTON_CUSTOMER_PROFILE'), Url::toRoute(['/signup/']), [
                'data-confirm' => $onlyReg
            ]);
        }
        else{
            $this->button = Html::a(ContractModule::t('GUEST_INTERFACE', 'VIEW_ELEMENT_TAPE_OF_ORDERS_BUTTON_CUSTOMER_PROFILE'), "javascript:void(0)", [
                'class' => 'order_link'
            ]);

            $this->jsHandler = '
                $(".order_link").click(function() {
                    var url = "' . Url::toRoute(['view']) . '";
                    $.pjax({url: url, container: "#'.$this->pjaxContainerId.'", data:{orderId: $(this).closest("tr").data("order")}, push:false, replace:false});
                });

                $("#'.$this->pjaxContainerId.'").on("pjax:end", function() {
                    initPopup();
                    initPage();
                    $("#order-modal").modal();
                });
            ';
        }
        parent::init();
    }
}