<?php

use yii\db\Schema;
use yii\db\Migration;

class m150213_054303_faq_create extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%faq}}', [
            'id' => Schema::TYPE_PK,
            'request' => Schema::TYPE_STRING . '(255) NOT NULL',
            'response' => Schema::TYPE_STRING . '(255) NOT NULL',
        ], $tableOptions . ' COMMENT "Часто задаваемые вопросы"');
        $this->createIndex('uidx_faq_request', 'faq', 'request', true);

        $this->createTable('{{%faq_lang}}', [
            'id' => Schema::TYPE_PK,
            'faq_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'lang_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'request' => Schema::TYPE_STRING . '(255) NOT NULL',
            'response' => Schema::TYPE_STRING . '(255) NOT NULL',
        ], $tableOptions);
        $this->addForeignKey('fk_faq_lang_faq', 'faq_lang', 'faq_id', 'faq', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_faq_lang_lang', 'faq_lang', 'lang_id', 'lang', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('uidx_faq_lang', 'faq_lang', 'faq_id, lang_id', 'request', true);
        $this->createIndex('uidx_faq_lang_request', 'faq_lang', 'faq_id, lang_id, request', true);
    }

    public function down()
    {
        $this->dropTable('faq_lang');
        $this->dropTable('faq');
    }
}
