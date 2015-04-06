<?php

namespace modules\event\models;

use modules\lang\models\Lang;
use Yii;

/**
 * This is the model class for table "event_lang".
 *
 * @property integer $id
 * @property integer $event_id
 * @property integer $lang_id
 * @property string $title
 * @property string $video
 *
 * @property EventDescription[] $eventDescriptions
 * @property Event $event
 * @property Lang $lang
 */
class EventLang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_lang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_id', 'lang_id'], 'required'],
            [['event_id', 'lang_id'], 'integer'],
            [['title', 'video'], 'string', 'max' => 255],
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
//            'lang_id' => Yii::t('app', 'Lang ID'),
//            'title' => Yii::t('app', 'Title'),
//            'video' => Yii::t('app', 'Video'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventDescriptions()
    {
        return $this->hasMany(EventDescription::className(), ['event_lang_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLang()
    {
        return $this->hasOne(Lang::className(), ['id' => 'lang_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Event::className(), ['id' => 'event_id']);
    }

    public static function getEventLangByEventAndLang($eventId, $langId){
        $langId = $langId ? $langId : Lang::getCurrent()->id;
        return EventLang::find()->where('event_id=:event AND lang_id=:lang', [
            'event'=>$eventId,
            'lang'=>$langId
        ]);
    }
}
