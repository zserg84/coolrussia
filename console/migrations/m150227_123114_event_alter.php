<?php

use yii\db\Schema;
use yii\db\Migration;

class m150227_123114_event_alter extends Migration
{
    public function up()
    {
        $this->addColumn('event_lang', 'title', Schema::TYPE_STRING . ' NOT NULL');
        $this->addColumn('event_lang', 'video', Schema::TYPE_STRING . ' DEFAULT NULL');
        $this->dropColumn('event_description', 'description');
        $this->dropForeignKey('fk_event_description_question', 'event_description');
        $this->dropColumn('event_description', 'question_id');
    }

    public function down()
    {
        $this->dropColumn('event_lang', 'title');
        $this->dropColumn('event_lang', 'video');
        $this->addColumn('event_description', 'description', Schema::TYPE_STRING);
        $this->addColumn('event_description', 'question_id', Schema::TYPE_INTEGER .' NOT NULL');
    }
}
