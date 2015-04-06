<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 03.02.15
 * Time: 10:22
 */

namespace modules\blog\models\backend;


use modules\blog\models\backend\BlogsLang;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;

class BlogsLangSearch extends BlogsLang{

    public $_langName;

    public function rules() {
        return [
            [['_langName', 'snippet', 'content', 'title', 'blog_id', 'lang_id'], 'safe']
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

        $query->joinWith(['lang']);
        $query->andFilterWhere(['blog_id' => $this->blog_id]);

        $dataProvider->sort->attributes['_langName'] = [
            'asc' => ['lang.name' => SORT_ASC],
            'desc' => ['lang.name' => SORT_DESC],
        ];
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'lang.name', $this->_langName]);
        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['like', 'snippet', $this->snippet]);
        $query->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
} 