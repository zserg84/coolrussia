<?php

use yii\db\Schema;
use yii\db\Migration;

class m150227_104726_question_answers_rebuild extends Migration
{
    public function up()
    {
        $this->addColumn('event_description', 'question_where', Schema::TYPE_STRING.' COMMENT "Куда пойдём?"');
        $this->addColumn('event_description', 'question_includeInPrice', Schema::TYPE_STRING.' COMMENT "Что входит в стоимость?"');
        $this->addColumn('event_description', 'question_take', Schema::TYPE_STRING.' COMMENT "Что взять с собой?"');
        $this->addColumn('event_description', 'question_why', Schema::TYPE_STRING.' COMMENT "Почему сюда стоит сходить?"');
        $this->addColumn('event_description', 'question_what', Schema::TYPE_STRING.' COMMENT "Что будем делать?"');
        $this->addColumn('event_description', 'question_extra', Schema::TYPE_STRING.' COMMENT "Дополнительно"');
        $this->addColumn('event_description', 'question_description', Schema::TYPE_STRING.' COMMENT "Описание"');

        $this->update('event_question', ['question'=>'question_where'], 'question="Куда пойдем?"');
        $this->update('event_question', ['question'=>'question_includeInPrice'], 'question="Что входит в стоимость?"');
        $this->update('event_question', ['question'=>'question_take'], 'question="Что взять с собой?"');
        $this->update('event_question', ['question'=>'question_why'], 'question="Почему сюда стоит сходить?"');
        $this->update('event_question', ['question'=>'question_what'], 'question="Что будем делать?"');
        $this->update('event_question', ['question'=>'question_extra'], 'question="Дополнительно"');
        $this->update('event_question', ['question'=>'question_description'], 'question="Описание"');
    }

    public function down()
    {
        echo "m150227_104726_question_answers_rebuild cannot be reverted.\n";

        return false;
    }
}
