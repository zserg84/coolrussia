<?php

namespace modules\event\models;

use Yii;

/**
 * This is the model class for table "event_cost".
 *
 * @property integer $id
 * @property integer $event_id
 * @property double $amount
 * @property integer $people_min
 * @property integer $people_max
 *
 * @property Event $event
 */
class EventCost extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_cost';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_id', 'amount', 'people_min'], 'required'],
            [['event_id', 'people_min', 'people_max'], 'integer'],
            [['amount'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
//            'id' => Yii::t('app', 'ID'),
//            'event_id' => Yii::t('app', 'Event ID'),
//            'amount' => Yii::t('app', 'Amount'),
//            'people_min' => Yii::t('app', 'People Min'),
//            'people_max' => Yii::t('app', 'People Max'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Event::className(), ['id' => 'event_id']);
    }
}
