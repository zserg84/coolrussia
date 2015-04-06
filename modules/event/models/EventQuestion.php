<?php

namespace modules\event\models;

use Yii;

/**
 * This is the model class for table "event_question".
 *
 * @property integer $id
 * @property integer $type_id
 * @property string $question
 * @property string $tooltip
 *
 * @property EventDescription[] $eventDescriptions
 * @property EventType $type
 */
class EventQuestion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_question';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id', 'question'], 'required'],
            [['type_id'], 'integer'],
            [['question', 'tooltip'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
//            'id' => Yii::t('app', 'ID'),
//            'type_id' => Yii::t('app', 'Type ID'),
//            'question' => Yii::t('app', 'Question'),
//            'tooltip' => Yii::t('app', 'Tooltip'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventDescriptions()
    {
        return $this->hasMany(EventDescription::className(), ['question_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(EventType::className(), ['id' => 'type_id']);
    }
}
