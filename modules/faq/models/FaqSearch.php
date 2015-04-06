<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 03.02.15
 * Time: 10:22
 */

namespace modules\faq\models;


use yii\data\ActiveDataProvider;

class FaqSearch extends Faq{

    public function rules() {
        return [
            [['request', 'response', 'status'], 'safe'],
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
        $query->andFilterWhere(['like', 'request', $this->request]);
        $query->andFilterWhere(['like', 'response', $this->response]);
        $query->andFilterWhere(['=', 'status', $this->status]);

        return $dataProvider;
    }
} 