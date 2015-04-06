<?php

use yii\db\Schema;
use yii\db\Migration;

class m150227_074251_event_tables extends Migration
{
    public function up()
    {
        $this->createTable('{{event_lang}}', [
            'id' => 'pk',
            'event_id' => Schema::TYPE_INTEGER .' NOT NULL',
            'lang_id' => Schema::TYPE_INTEGER .' NOT NULL',
        ]);
        $this->addForeignKey('fk_event_lang_event', 'event_lang', 'event_id', 'event', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_event_lang_lang', 'event_lang', 'lang_id', 'lang', 'id', 'CASCADE', 'CASCADE');

        $this->dropForeignKey('fk_event_description_event', 'event_description');
        $this->dropForeignKey('fk_event_description_lang', 'event_description');
        $this->dropColumn('event_description', 'lang_id');
        $this->dropColumn('event_description', 'event_id');

        $this->addColumn('event_description', 'event_lang_id', Schema::TYPE_INTEGER);
        $this->addForeignKey('fk_event_description_event_lang', 'event_description', 'event_lang_id', 'event_lang', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {

    }
}
