<?php

use yii\db\Schema;
use yii\db\Migration;

class m150202_112922_blogs_lang_create extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%blogs_lang}}', [
            'id' => Schema::TYPE_PK,
            'blog_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'lang_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'title' => Schema::TYPE_STRING . '(100) NOT NULL',
            'snippet' => Schema::TYPE_TEXT . ' NOT NULL',
            'content' => Schema::TYPE_TEXT . ' NOT NULL',
        ], $tableOptions);

        $this->addForeignKey('fk_blog_lang_blog', '{{%blogs_lang}}', 'blog_id', '{{%blogs}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_blog_lang_lang', '{{%blogs_lang}}', 'lang_id', '{{%lang}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%blogs_lang}}');
    }
}
