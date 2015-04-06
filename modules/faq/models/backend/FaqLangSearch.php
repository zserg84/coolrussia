<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 03.02.15
 * Time: 10:22
 */

namespace modules\faq\models\backend;


use modules\faq\models\FaqLang;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;

class FaqLangSearch extends FaqLang{

    public function rules() {
        return [
            [['faq_id', 'lang_id', 'request', 'response'], 'safe'],
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

        $query->joinWith(['faq']);
        $query->andFilterWhere(['faq_id' => $this->faq_id]);


        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['=', 'faq_id', $this->faq_id]);
        $query->andFilterWhere(['=', 'lang_id', $this->lang_id]);
        $query->andFilterWhere(['like', 'request', $this->request]);
        $query->andFilterWhere(['like', 'response', $this->response]);

        return $dataProvider;
    }
} 