<?php

namespace modules\event\models;

use modules\event\Module;
use Yii;

/**
 * This is the model class for table "event_description".
 *
 * @property integer $id
 * @property integer $event_lang_id
 * @property string $question_where
 * @property string $question_includeInPrice
 * @property string $question_take
 * @property string $question_why
 * @property string $question_what
 * @property string $question_extra
 * @property string $question_description
 *
 * @property EventLang $eventLang
 * @property EventQuestion $question
 */
class EventDescription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_description';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_lang_id'], 'integer'],
            [['question_where', 'question_includeInPrice', 'question_take', 'question_why', 'question_what', 'question_extra', 'question_description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
//            'id' => Yii::t('app', 'ID'),
//            'event_lang_id' => Yii::t('app', 'Event Lang ID'),
            'question_where' => Module::t('event_description', 'QUESTION_WHERE'),
            'question_includeInPrice' => Module::t('event_description', 'QUESTION_INCLUDE_IN_PRICE'),
            'question_take' => Module::t('event_description', 'QUESTION_TAKE'),
            'question_why' => Module::t('event_description', 'QUESTION_WHY'),
            'question_what' => Module::t('event_description', 'QUESTION_WHAT'),
            'question_extra' => Module::t('event_description', 'QUESTION_EXTRA'),
            'question_description' => Module::t('event_description', 'QUESTION_DESCRIPTION'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventLang()
    {
        return $this->hasOne(EventLang::className(), ['id' => 'event_lang_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(EventQuestion::className(), ['id' => 'question_id']);
    }
}
