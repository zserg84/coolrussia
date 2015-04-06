<?php

namespace modules\translations\models\search;

use yii\data\ActiveDataProvider;
use Yii;
use yii\helpers\ArrayHelper;
use modules\translations\models\SourceMessage;
use modules\translations\Module;

class SourceMessageSearch extends SourceMessage
{
    const STATUS_TRANSLATED = 1;
    const STATUS_NOT_TRANSLATED = 2;

    public $status;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['category_id', 'safe'],
            ['message', 'safe'],
            ['status', 'safe']
        ];
    }

    /**
     * @param array|null $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = SourceMessage::find();
        $dataProvider = new ActiveDataProvider(['query' => $query]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if ($this->status == static::STATUS_TRANSLATED) {
            $query->translated();
        }
        if ($this->status == static::STATUS_NOT_TRANSLATED) {
            $query->notTranslated();
        }

        $query->andFilterWhere([
            'category_id' => $this->category_id
        ]);
        $query->andFilterWhere(['like', 'message', $this->message]);
        return $dataProvider;
    }

    public static function getStatus($id = null)
    {
        $statuses = [
            self::STATUS_TRANSLATED => Module::t('translations', 'Translated'),
            self::STATUS_NOT_TRANSLATED => Module::t('translations', 'Not translated'),
        ];
        if ($id !== null) {
            return ArrayHelper::getValue($statuses, $id, null);
        }
        return $statuses;
    }
}
