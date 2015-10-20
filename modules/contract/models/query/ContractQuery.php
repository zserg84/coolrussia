<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 24.04.15
 * Time: 13:27
 */

namespace modules\contract\models\query;

use modules\contract\models\Contract;
use yii\db\ActiveQuery;

class ContractQuery extends ActiveQuery
{

    public function city($cityId = null){
        return $this->innerJoinWith([
            'city' => function($query) use($cityId){
                if($cityId)
                    $query->where(['city.ID' => $cityId]);
            }
        ]);
    }

    public function state($stateId = null){
        return $this->innerJoinWith([
            'city.state' => function($query) use($stateId){
                if($stateId)
                    $query->where(['state.id' => $stateId]);
            }
        ]);
    }

    public function country($countryId = null){
        return $this->innerJoinWith([
            'city.country' => function($query) use($countryId){
                $query->from('country c1');
                if($countryId)
                    $query->where(['c1.id' => $countryId]);
            },
            'city.state.country' => function($query) use($countryId){
                $query->from('country c2');
                if($countryId)
                    $query->where(['c2.id' => $countryId]);
            }
        ]);
    }

    /*
     * Избранные контракты для контракта $contractId
     * */
    public function favorite($contractId){
        return $this->innerJoinWith([
            'favoriteCompanies' => function($query) use($contractId){
                $query->where(['contract_id' => $contractId]);
            }
        ]);
    }

} 