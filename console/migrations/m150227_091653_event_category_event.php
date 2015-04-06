<?php

use yii\db\Schema;
use yii\db\Migration;

class m150227_091653_event_category_event extends Migration
{
    public function up()
    {
        $this->createTable('{{event_category_event}}', [
            'event_id' => Schema::TYPE_INTEGER .' NOT NULL',
            'category_id' => Schema::TYPE_INTEGER . ' NOT NULL',
        ]);
        $this->addPrimaryKey('pk_event_category_event', 'event_category_event', 'event_id, category_id');
        $this->addForeignKey('fk_event_category_event_event', 'event_category_event', 'event_id', 'event', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_event_category_event_category', 'event_category_event', 'category_id', 'event_category', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('event_category_event');
    }
}
