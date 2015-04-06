<?php

namespace modules\event\models;

use Yii;

/**
 * This is the model class for table "event_type".
 *
 * @property integer $id
 * @property string $name
 *
 * @property EventCategoryType[] $eventCategoryTypes
 * @property EventCategory[] $categories
 * @property EventQuestion[] $eventQuestions
 */
class EventType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
//            'id' => Yii::t('app', 'ID'),
//            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventCategoryTypes()
    {
        return $this->hasMany(EventCategoryType::className(), ['type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(EventCategory::className(), ['id' => 'category_id'])->viaTable('event_category_type', ['type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventQuestions()
    {
        return $this->hasMany(EventQuestion::className(), ['type_id' => 'id']);
    }

    /**
     * Список типов вопросов.
     * $questions - список вопросов
     * */
    public static function getQuestionTypes($questions){
        $types = [];
        foreach($questions as $questionModel){
            $types[$questionModel->type_id] = $questionModel->type_id;
        }
        return $types;
    }
}
