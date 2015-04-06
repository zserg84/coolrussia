<?php

namespace modules\translations\models;

use modules\lang\models\Lang;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use modules\translations\Module;

class Message extends ActiveRecord
{
    /**
     * @return string
     * @throws InvalidConfigException
     */
    public static function tableName()
    {
        $i18n = Yii::$app->getI18n();
        if (!isset($i18n->messageTable)) {
            throw new InvalidConfigException('You should configure i18n component');
        }
        return $i18n->messageTable;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lang_id', 'source_message_id'], 'required'],
            ['translation', 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('translations', 'ID'),
            'lang_id' => Module::t('translations', 'LANGUAGE'),
            'translation' => Module::t('translations', 'TRANSLATIONS')
        ];
    }

    public function getSourceMessage()
    {
        return $this->hasOne(SourceMessage::className(), ['id' => 'source_message_id']);
    }

    public function getLang()
    {
        return $this->hasOne(Lang::className(), ['id' => 'lang_id']);
    }
}
