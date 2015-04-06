<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 05.03.15
 * Time: 15:55
 */

namespace modules\event\models\search;

use modules\event\models\Event;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;

class EventSearch extends Event {

    public $pageSize = 20;

    public $event_categories;

    private $_dataProvider;

    public function rules()
    {
        return [
            [['cost', 'duration', 'date'], 'safe'],
        ];
    }

    public function search()
    {
        $query = Event::find()->active()->actual();

        /**
         * Создаём DataProvider, указываем ему запрос, настраиваем пагинацию
         */
        $this->_dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => new Pagination([
                'pageSize' => $this->pageSize
            ])
        ]);

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['=', 'duration', $this->duration]);

        return $this->_dataProvider;
    }

    public function buildModels()
    {
        return $this->_dataProvider->getModels();
    }

    public function getDataProvider()
    {
        return $this->_dataProvider;
    }
} 