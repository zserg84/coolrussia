<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 26.05.15
 * Time: 11:20
 */

namespace modules\contract\widgets\actionButtons\orders;

use modules\contract\Module as ContractModule;
use modules\contract\widgets\actionButtons\Button;
use yii\helpers\Html;
use yii\helpers\Url;

/*
 * Кнопка "Удалить отклик"
 * */
class ResponseDeleteButton extends Button
{
    public function init()
    {
        $this->button = Html::a(ContractModule::t('PERFORMER_INTERFACE', 'VIEW_ELEMENT_PERFORMER_3_REMOVE_BUTTON'), Url::toRoute(['response-delete', 'orderId'=>$this->model->id]), [
            'data-confirm' => 'Вы подтвержаете удаление отклика ?',
            'class' => 'response_delete_link',
        ]);

        parent::init();
    }
} 