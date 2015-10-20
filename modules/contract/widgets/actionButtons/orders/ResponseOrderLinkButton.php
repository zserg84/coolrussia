<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 26.05.15
 * Time: 11:13
 */

namespace modules\contract\widgets\actionButtons\orders;

use modules\contract\Module as ContractModule;
use modules\contract\widgets\actionButtons\Button;
use yii\helpers\Html;
use yii\helpers\Url;

/*
 * Кнопка "Отклик на заказ"
 * */
class ResponseOrderLinkButton extends Button
{
    public function init()
    {
        $this->button = Html::a(ContractModule::t('PERFORMER_INTERFACE', 'VIEW_ELEMENT_TAPE_OF_ORDERS_1_2_ANSWER_TO_THE_ORDER_BUTTON'), "javascript:void(0)", [
            'class' => 'response_order_link'
        ]);

        $this->jsHandler = '
            $(".response_order_link").click(function(){
                var url = "'.Url::toRoute(['response']).'";
                $.pjax({
                    url: url,
                    container: "#'.$this->pjaxContainerId.'",
                    data:{orderId: $(this).closest("tr").data("order")},
                    push:false,
                    replace:false
                });
            });

            $("#'.$this->pjaxContainerId.'").on("pjax:end", function() {
                initPopup();
                initPage();
                $("#response-modal").modal();
            });
        ';

        parent::init();
    }
} 