<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 03.02.15
 * Time: 10:22
 */

namespace modules\direction\models\search;


use modules\direction\models\Directionlang;
use yii\data\ActiveDataProvider;

class DirectionlangSearch extends Directionlang{

    public function rules() {
        return [
            [['direction_id', 'lang_id', 'translate'], 'safe'],
        ];
    }

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

        $query->joinWith(['direction']);
        $query->andFilterWhere(['direction_id' => $this->direction_id]);


        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['=', 'direction_id', $this->direction_id]);
        $query->andFilterWhere(['=', 'lang_id', $this->lang_id]);
        $query->andFilterWhere(['like', 'translate', $this->translate]);

        return $dataProvider;
    }
} 