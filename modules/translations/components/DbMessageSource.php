<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 12.02.15
 * Time: 16:43
 */

namespace modules\translations\components;


use yii\db\Query;
use yii\helpers\ArrayHelper;

class DbMessageSource extends \yii\i18n\DbMessageSource
{

    public $messageCategoryTable = '{{%message_category}}';

    public $langTable = '{{%lang}}';

    protected function loadMessagesFromDb($category, $language)
    {
        $mainQuery = new Query();
        $mainQuery->select(['t1.message message', 't2.translation translation'])
            ->from(["$this->sourceMessageTable t1", "$this->messageTable t2", "$this->messageCategoryTable t3", "$this->langTable t4"])
            ->where('t1.category_id = t3.id AND t1.id = t2.source_message_id and t4.id=t2.lang_id and t4.local = :language and t3.name=:category')
//            ->where('t1.id = t2.id AND t1.category = :category AND t2.language = :language')
            ->params([':category' => $category, ':language' => $language]);

        $fallbackLanguage = substr($language, 0, 2);
        if ($fallbackLanguage != $language) {
            $fallbackQuery = new Query();
            $fallbackQuery->select(['t1.message message', 't2.translation translation'])
                ->from(["$this->sourceMessageTable t1", "$this->messageTable t2", "$this->messageCategoryTable t3", "$this->langTable t4"])
                ->where('t1.category_id = t3.id AND t1.id = t2.source_message_id and t4.id=t2.lang_id and t4.local = :language and t3.name=:category')
//                ->where('t1.id = t2.id AND t1.category = :category AND t2.language = :fallbackLanguage')
                ->andWhere("t2.id NOT IN (SELECT mt.id FROM $this->messageTable mt INNER JOIN $this->langTable lt ON lt.id = mt.lang_id
                    WHERE lt.local = :language)")
                ->params([':category' => $category, ':language' => $language, ':fallbackLanguage' => $fallbackLanguage]);

            $mainQuery->union($fallbackQuery, true);
        }

        $messages = $mainQuery->createCommand($this->db)->queryAll();

        return ArrayHelper::map($messages, 'message', 'translation');
    }
} 