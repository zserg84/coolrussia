<?php

namespace modules\event\models;

use Yii;

/**
 * This is the model class for table "event_category_type".
 *
 * @property integer $category_id
 * @property integer $type_id
 *
 * @property EventType $type
 * @property EventCategory $category
 */
class EventCategoryType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_category_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'type_id'], 'required'],
            [['category_id', 'type_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
//            'category_id' => Yii::t('app', 'Category ID'),
//            'type_id' => Yii::t('app', 'Type ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(EventType::className(), ['id' => 'type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(EventCategory::className(), ['id' => 'category_id']);
    }
}
