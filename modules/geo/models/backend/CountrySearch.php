<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 26.02.15
 * Time: 14:59
 */

namespace modules\geo\models\backend;

use modules\geo\models\GeoCountry;
use yii\data\ActiveDataProvider;

class CountrySearch extends GeoCountry
{

    public function rules() {
        return [
            [['name_ru', 'name_en', 'code', 'sort'], 'safe'],
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

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['=', 'sort', $this->sort]);
        $query->andFilterWhere(['like', 'code', $this->code]);
        $query->andFilterWhere(['like', 'name_ru', $this->name_ru]);
        $query->andFilterWhere(['like', 'name_en', $this->name_en]);

        return $dataProvider;
    }
} 