<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 26.05.15
 * Time: 12:13
 */

namespace modules\contract\widgets\actionButtons\performers;

use modules\contract\models\Contract;
use modules\contract\models\FavoriteCompany;
use modules\contract\Module as ContractModule;
use modules\contract\widgets\actionButtons\Button;
use yii\helpers\Html;
use yii\helpers\Url;

class FavoriteAddButton extends Button
{

    public function init()
    {
        $currentContract = Contract::getCurContract();
        $favoriteCompany = FavoriteCompany::find()->where([
            'contract_id' => $currentContract->id,
            'favorite_contract_id' => $this->model->id,
        ])->one();

        $this->button = !$favoriteCompany ? Html::a(ContractModule::t('CUSTOMER_INTERFACE', 'VIEW_ELEMENT_PERFORMER_FAVOURITES_BUTTON'), Url::toRoute(['favorite-add', 'favoriteContractId' => $this->model->id]), [
            'data-confirm' => "Действительно желаете добавить в избранное ?"
        ]) : null;

        parent::init();
    }
} 