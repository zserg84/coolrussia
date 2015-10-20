<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 23.04.15
 * Time: 16:59
 */

namespace modules\contract\models\search;

use common\components\FilterModelBase;
use modules\direction\models\Direction;
use modules\contract\models\Contract;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\helpers\VarDumper;

class PerformerSearch extends FilterModelBase
{

    public $filter_specialization;

    public $filter_territory_country;
    public $filter_territory_state;
    public $filter_territory_city;

    public $filter_direction_checkbox;

    /*
     * Флаг избранных контрактов
     * */
    public $favorite = false;

    public function rules(){
        return [
            [['filter_specialization',
                'filter_territory_country', 'filter_territory_state', 'filter_territory_city',
                'filter_direction_checkbox',
                'favorite',
            ], 'safe'],
        ];
    }

    public function search($params = []){
        $this->initParams($params);

        $model = new Contract();
        $model->load($params);
        $this->load($params, '');
        $query = $model->find();

        $query->where('performer_id IS NOT NULL');

        $query->innerJoinWith([
            'performer' => function ($query) {
                if($this->filter_specialization)
                    $query->where(['specialization' => $this->filter_specialization]);
            }
        ]);
        $query->city($this->filter_territory_city);
        $query->state($this->filter_territory_state);
        $query->country($this->filter_territory_country);

        if($this->filter_direction_checkbox){
            $directions = [];
            foreach($this->filter_direction_checkbox as $directionId){
                $dir = Direction::findOne($directionId);
                $directions = array_merge($directions, $dir->getAllChildrenArray());
            }
            $query->innerJoinWith([
                'directions' => function($query) use($directions){
                    $query->where(['direction.ID' => $directions]);
                }
            ]);
        }

        if($this->favorite){
            $curContract = Contract::getCurContract();
            if($curContract)
                $query->favorite($curContract->id);
        }

        $this->_dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => new Pagination([
                'pageSize' => $this->pageSize
            ])
        ]);

        return $this->_dataProvider;
    }

    public function initParams($params){
        foreach($params as $paramKey=>$paramValue){
            if(isset($this->$paramKey))
                $this->$paramKey = $paramValue;
        }
    }
} 