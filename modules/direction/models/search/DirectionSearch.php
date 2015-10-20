<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 03.02.15
 * Time: 10:22
 */

namespace modules\direction\models\search;


use modules\direction\models\Direction;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;

class DirectionSearch extends Direction
{

    public $parentName;

    public function rules() {
        return [
            [['parent_id', 'name', 'parentName'], 'safe'],
        ];
    }

    /* Геттер для имени родителя */
    public function getParentName() {
        return $this->parent ? $this->parent->name : null;
    }
//    public function setParentName($name) {
//        return $this->_parentName = $name;
//    }

    /**
     * Creates data provider instance with search query applied.
     *
     * @param array $params Search params
     *
     * @return ActiveDataProvider DataProvider
     */
    public function search($params)
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if($this->parent_id)
            $query->andFilterWhere(['=', 'direction.parent_id', $this->parent_id]);

        $query->andFilterWhere(['like', 'direction.name', $this->name]);

        if($this->parentName)
            $query->joinWith(['parent' => function ($q) {
                $q->from('direction as parent_direction');
                $q->where('parent_direction.name LIKE "%' . $this->parentName . '%" ');
            }]);

        return $dataProvider;
    }
} 