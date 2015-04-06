<?php

namespace modules\translations\components;

use modules\lang\models\Lang;
use yii\base\InvalidConfigException;
use yii\i18n\DbMessageSource;
use yii\i18n\PhpMessageSource;

class I18N extends \yii\i18n\I18N
{
    /** @var string */
    public $sourceMessageTable = '{{%source_message}}';
    /** @var string */
    public $messageTable = '{{%message}}';
    /** @var string */
    public $messageCategoryTable = '{{%message_category}}';
    /** @var array */
    public $languages;
    /** @var array */
    public $missingTranslationHandler = ['modules\translations\Module', 'missingTranslation'];

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (!$this->languages) {
//            $db = \Yii::$app->db;
//            $langExist = $db->createCommand("SHOW TABLES LIKE :table", [':table'=>Lang::tableName()])->queryScalar();
            try {
                $languages = Lang::find()->all();
                foreach($languages as $language){
                    $this->languages[] = $language->url;
                }
            } catch (\Exception $e) {
                echo $e->getMessage()."\n";
            }
//            throw new InvalidConfigException('You should configure i18n component [language]');
        }

        if (!isset($this->translations['*'])) {
            $this->translations['*'] = [
                'class' => DbMessageSource::className(),
                'sourceMessageTable' => $this->sourceMessageTable,
                'messageTable' => $this->messageTable,
                'on missingTranslation' => $this->missingTranslationHandler
            ];
        }
        if (!isset($this->translations['app']) && !isset($this->translations['app*'])) {
            $this->translations['app'] = [
                'class' => PhpMessageSource::className(),
//                'sourceMessageTable' => $this->sourceMessageTable,
//                'messageTable' => $this->messageTable,
//                'on missingTranslation' => $this->missingTranslationHandler
            ];
        }
        parent::init();
    }
}
