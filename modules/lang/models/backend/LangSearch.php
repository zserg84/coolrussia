<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 03.02.15
 * Time: 10:22
 */

namespace modules\lang\models\backend;


use modules\lang\models\Lang;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;

class LangSearch extends Lang{

    public function rules() {
        return [
            [['url', 'local', 'name', 'date_update', 'date_create'], 'safe'],
            [['date_create', 'date_update'], 'date', 'format' => 'd.m.Y']
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

        $query->andFilterWhere(
            [
                'FROM_UNIXTIME(date_create, "%d.%m.%Y")' => $this->date_create,
                'FROM_UNIXTIME(date_update, "%d.%m.%Y")' => $this->date_update
            ]
        );

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'url', $this->url]);
        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['like', 'local', $this->local]);

        return $dataProvider;
    }
} 