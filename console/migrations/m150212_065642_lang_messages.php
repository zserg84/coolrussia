<?php

use yii\db\Schema;
use yii\db\Migration;

class m150212_065642_lang_messages extends Migration
{
    public function up()
    {
        $tableOptions = ($this->db->driverName === 'mysql') ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' : null;
//
//        $this->createTable('{{%module}}', [
//            'id' => Schema::TYPE_PK,
//            'name' => Schema::TYPE_STRING . ' NOT NULL',
//        ], $tableOptions. ' COMMENT "Модули проекта"');
//
//        $modules = ['main', 'users', 'lang', 'blog', 'blogslang', ];
//        foreach($modules as $module){
//            $this->insert('module', [
//                'name' => $module,
//            ]);
//        }
//
//        $this->createTable('{{%messages_translate}}', [
//            'id' => Schema::TYPE_PK,
//            'module_id' => Schema::TYPE_INTEGER . ' NOT NULL',
//            'lang_id' => Schema::TYPE_INTEGER . ' NOT NULL',
//            'name' => Schema::TYPE_STRING . '(100) NOT NULL',
//            'translate' => Schema::TYPE_TEXT . ' NOT NULL',
//            'comment' => Schema::TYPE_TEXT . ' DEFAULT NULL',
//        ], $tableOptions. ' COMMENT "Перевод элементов интерфейса"');
//        $this->addForeignKey('fk_messages_translate_module', '{{messages_translate}}', 'module_id', '{{module}}', 'id', 'CASCADE', 'CASCADE');
//        $this->addForeignKey('fk_messages_translate_lang', '{{messages_translate}}', 'lang_id', '{{lang}}', 'id', 'CASCADE', 'CASCADE');



        $i18n = Yii::$app->getI18n();
        if (!isset($i18n->sourceMessageTable) || !isset($i18n->messageTable)) {
            throw new InvalidConfigException('You should configure i18n component');
        }
        $messageCategory = $i18n->messageCategoryTable;
        $sourceMessageTable = $i18n->sourceMessageTable;
        $messageTable = $i18n->messageTable;

        $this->createTable($messageCategory, [
            'id' => Schema::TYPE_PK,
            'name' => 'varchar(32) null',
        ], $tableOptions);

        $this->createTable($sourceMessageTable, [
            'id' => Schema::TYPE_PK,
            'category_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'message' => 'text null'
        ], $tableOptions);
        $this->addForeignKey('fk_message_category_category', $sourceMessageTable, 'category_id', $messageCategory, 'id', 'cascade');

        $this->createTable($messageTable, [
            'id' => Schema::TYPE_PK,
            'source_message_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'lang_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'translation' => Schema::TYPE_TEXT . ' DEFAULT NULL'
        ], $tableOptions);
//        $this->addPrimaryKey('id', $messageTable, ['id', 'language']);
        $this->addForeignKey('fk_source_message_message', $messageTable, 'source_message_id', $sourceMessageTable, 'id', 'cascade');
        $this->addForeignKey('fk_source_message_lang', $messageTable, 'lang_id', 'lang', 'id', 'cascade');
    }

    public function down()
    {
        $i18n = Yii::$app->getI18n();
        $messageCategory = $i18n->messageCategoryTable;
        $sourceMessageTable = $i18n->sourceMessageTable;
        $messageTable = $i18n->messageTable;

        $this->dropForeignKey('fk_source_message_lang', $messageTable);
        $this->dropForeignKey('fk_source_message_message', $messageTable);
        $this->dropTable($messageTable);

        $this->dropForeignKey('fk_message_category_category', $sourceMessageTable);
        $this->dropTable($sourceMessageTable);

        $this->dropTable($messageCategory);
    }
}
