<?php

namespace modules\page\models;

use yii\data\ActiveDataProvider;

class PageSearch extends Page {

    public function rules() {
        return [
            [['url', 'lang_id', 'title', 'visibility'], 'safe'],
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
        $query->andFilterWhere(['like', 'url', $this->url]);
        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['=', 'visibility', $this->visibility]);
        $query->andFilterWhere(['=', 'lang_id', $this->lang_id]);

        return $dataProvider;
    }
} 