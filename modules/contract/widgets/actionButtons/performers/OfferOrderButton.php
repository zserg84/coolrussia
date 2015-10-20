<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 26.05.15
 * Time: 12:22
 */

namespace modules\contract\widgets\actionButtons\performers;

use modules\contract\Module as ContractModule;
use modules\contract\widgets\actionButtons\Button;
use yii\helpers\Html;
use yii\helpers\Url;

class OfferOrderButton extends Button
{

    public function init()
    {
        if(\Yii::$app->getUser()->isGuest){
            $onlyReg = 'Просмотр информации возможен только зарегистрированными пользователями';
            $this->button = Html::a(ContractModule::t('CUSTOMER_INTERFACE', 'VIEW_ELEMENT_PERFORMER_TO_OFFER_THE_ORDER_BUTTON'), Url::toRoute(['/signup/']), [
                'data-confirm' => $onlyReg
            ]);
        }
        else{
            $this->button = Html::a(ContractModule::t('CUSTOMER_INTERFACE', 'VIEW_ELEMENT_PERFORMER_TO_OFFER_THE_ORDER_BUTTON'), "javascript:void(0)", [
                'class' => 'offer_order_link'
            ]);

            $this->jsHandler = '
                $(".offer_order_link").click(function() {
                    var url = "'.Url::toRoute(['/contract/performer/offer-order']).'";
                    $.pjax({url: url, container: "#'.$this->pjaxContainerId.'", data:{contractId: $(this).closest("tr").data("contract")}, push:false, replace:false});
                });

                $("#'.$this->pjaxContainerId.'").on("pjax:end", function() {
                    initPopup();
                    $("#offer-order-modal").modal();
                });
            ';
        }

        parent::init();
    }
} 