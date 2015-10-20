<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 26.05.15
 * Time: 12:04
 */
namespace modules\contract\widgets\actionButtons\performers;

use modules\contract\Module as ContractModule;
use modules\contract\widgets\actionButtons\Button;
use yii\helpers\Html;
use yii\helpers\Url;

class ProfileButton extends Button
{
    public function init()
    {
        if(\Yii::$app->getUser()->isGuest){
            $onlyReg = 'Просмотр информации возможен только зарегистрированными пользователями';
            $this->button = Html::a(ContractModule::t('CUSTOMER_INTERFACE', 'VIEW_ELEMENT_PERFORMER_PROFILE_BUTTON'), Url::toRoute(['/signup/']), [
                'data-confirm' => $onlyReg
            ]);
        }
        else{
            $this->button = Html::a(ContractModule::t('CUSTOMER_INTERFACE', 'VIEW_ELEMENT_PERFORMER_PROFILE_BUTTON'), "javascript:void(0)", [
                'class' => 'profile_link'
            ]);

            $this->jsHandler = '
                $(".profile_link").click(function() {
                    var url = "'.Url::toRoute(['profile']).'";
                    $.pjax({url: url, container: "#'.$this->pjaxContainerId.'", data:{contractId: $(this).closest("tr").data("contract")}, push:false, replace:false});
                });

                $("#'.$this->pjaxContainerId.'").on("pjax:end", function() {
                    initPopup();
                    $("#profile-modal").modal();
                });
            ';
        }


        parent::init();
    }
} 