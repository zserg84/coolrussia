<?php

namespace modules\translations\models;

use modules\lang\models\Lang;
use yii\base\InvalidConfigException;
use Yii;
use yii\db\ActiveRecord;
use modules\translations\models\query\SourceMessageQuery;
use modules\translations\Module;
use yii\helpers\VarDumper;

class SourceMessage extends ActiveRecord
{
    /**
     * @return string
     * @throws InvalidConfigException
     */
    public static function tableName()
    {
        $i18n = Yii::$app->getI18n();
        if (!isset($i18n->sourceMessageTable)) {
            throw new InvalidConfigException('You should configure i18n component');
        }
        return $i18n->sourceMessageTable;
    }

    public static function find()
    {
        return new SourceMessageQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['message', 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('translations', 'ID'),
            'category_id' => Module::t('translations', 'CATEGORY'),
            'message' => Module::t('translations', 'MESSAGE'),
            'status' => Module::t('translations', 'STATUS')
        ];
    }

    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['source_message_id' => 'id'])->indexBy('lang_id');
    }

    public function getCategory()
    {
        return $this->hasOne(MessageCategory::className(), ['id' => 'category_id']);
    }

    public function initMessages()
    {
        $messages = [];
        $languages = Lang::find()->all();
        foreach ($languages as $languageModel) {
            $langId = $languageModel->id;
            if (!isset($this->messages[$langId])) {
                $message = new Message;
                $message->lang_id = $languageModel->id;
                $message->source_message_id = $this->id;
                $messages[$langId] = $message;
            } else {
                $messages[$langId] = $this->messages[$langId];
            }
        }
        $this->populateRelation('messages', $messages);
    }

    public function saveMessages()
    {
        /** @var Message $message */
        foreach ($this->messages as $message) {
            $this->link('messages', $message);
            $message->save();
        }
    }
}
