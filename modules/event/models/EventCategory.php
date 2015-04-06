<?php

namespace modules\event\models;

use modules\event\models\query\EventCategoryQuery;
use Yii;

/**
 * This is the model class for table "event_category".
 *
 * @property integer $id
 * @property string $name
 *
 * @property EventCategoryEvent[] $eventCategoryEvents
 * @property Event[] $events
 * @property EventCategoryType[] $eventCategoryTypes
 * @property EventType[] $types
 */
class EventCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%event_category}}';
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
    public function getEventCategoryEvents()
    {
        return $this->hasMany(EventCategoryEvent::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Event::className(), ['id' => 'event_id'])->viaTable('event_category_event', ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventCategoryTypes()
    {
        return $this->hasMany(EventCategoryType::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTypes()
    {
        return $this->hasMany(EventType::className(), ['id' => 'type_id'])->viaTable('event_category_type', ['category_id' => 'id']);
    }

    public static function find()
    {
        return new EventCategoryQuery(get_called_class());
    }
}
