<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 23.04.15
 * Time: 16:59
 */

namespace modules\contract\models\search;

use common\components\FilterModelBase;
use modules\contract\models\Contract;
use modules\contract\models\Order;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;

class OrderSearch extends FilterModelBase
{
    public $contract_id;

    public $withMyResponse;

    public function search($params = []){
        $model = new Order();
        $model->load($params);
        $query = $model->find();

        $query->andFilterWhere(['order.contract_id' => $this->contract_id]);

        if($this->withMyResponse){
            $curContract = Contract::getCurContract();
            $query->innerJoinWith([
                'offerToCustomers' => function($query) use($curContract){
                    $query->from('offer_to_customer as OTC');
                    $query->where([
                        'OTC.contract_id' => $curContract->id,
                    ]);
                }
            ]);
        }

        $this->_dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => new Pagination([
                'pageSize' => $this->pageSize
            ])
        ]);

        return $this->_dataProvider;
    }
}