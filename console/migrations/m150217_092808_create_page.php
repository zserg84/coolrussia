<?php

use yii\db\Schema;
use yii\db\Migration;

class m150217_092808_create_page extends Migration
{
    public function up()
    {
        $tableOptions = ($this->db->driverName === 'mysql') ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' : null;

        $this->createTable('{{%page}}', array(
            'id'=>'pk',
            'lang_id'=>'int(11) DEFAULT NULL',
            'url'=>'string',
            'title'=>'string',
            'txt'=>'text',
            'visibility'=>'tinyint(1) NOT NULL DEFAULT "1"',
            'create_time'=>'int(11)',
            'update_time'=>'int(11)',
        ), $tableOptions);

        $this->createIndex('unq__lang_id__url', '{{%page}}', ['lang_id', 'url'], true);

        $this->addForeignKey('fk__page__lang_id', '{{%page}}', 'lang_id', '{{%lang}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk__page__lang_id', '{{%page}}');

        $this->dropIndex('unq__lang_id__url', '{{%page}}');

        $this->dropTable('{{%page}}');
    }
}
