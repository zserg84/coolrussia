<?php

use yii\db\Schema;
use yii\db\Migration;

class m150216_053501_event extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%event}}', [
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING . '(255) NOT NULL',
            'email' => Schema::TYPE_STRING . '(50) NOT NULL',
            'phone' => Schema::TYPE_STRING . '(50) NOT NULL',
            'duration_type' => 'ENUM("Day", "Hour") NOT NULL',
            'duration' => Schema::TYPE_INTEGER . ' NOT NULL',
            'address' => Schema::TYPE_STRING . '(255) DEFAULT NULL COMMENT "Адрес в формате «город, улица, дом»"',
            'address_comment' => Schema::TYPE_STRING . '(255) DEFAULT NULL COMMENT "Коммент к адресу в свободной форме"',
            'time_type' => 'ENUM("Concrete", "Repeat", "Free") NOT NULL',
            'cost_type' => 'ENUM("Free", "One", "Many") NOT NULL',
            'prepayment' => Schema::TYPE_INTEGER . ' DEFAULT 0 COMMENT "Предоплата"',
        ], $tableOptions);

        $this->createTable('{{%event_type}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
        ], $tableOptions);
        $types = ['Экскурсия', 'Приключения', 'Мастер-класс', 'Благотворительность'];
        foreach($types as $k=>$type){
            $this->insert('event_type', ['id'=>$k+1, 'name'=>$type]);
        }

        $this->createTable('{{%event_category}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
        ], $tableOptions);

        $this->createTable('{{%event_category_type}}', [
            'category_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'type_id' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);
        $this->addPrimaryKey('pk_event_category_type', 'event_category_type', 'category_id, type_id');
        $this->addForeignKey('fk_event_category_type_category', 'event_category_type', 'category_id', 'event_category', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_event_category_type_type', 'event_category_type', 'type_id', 'event_type', 'id', 'CASCADE', 'CASCADE');

        $categories = [
            'Обзорные экскурсии', 'История и современность', 'Архитектура', 'Спорт', 'Культура и искусство', 'Романтика', 'Еда и напитки', 'Ночная жизнь',
            'Активный и экстремальный отдых', 'Шоппинг и стиль', 'Фотосессии', 'Прогулки',
            'Искусство', 'Деловые', 'Фотография',
            'Благотворительные акции',
        ];
        foreach($categories as $k=>$category){
            $this->insert('event_category', ['id'=>$k+1, 'name'=>$category]);

            if($k<8)
                $typeId = 1;
            if(($k>=8 && $k < 12) || ($k==5 || $k==6 || $k==7))
                $typeId = 2;
            if(($k>=12 && $k<15) || ($k==6 || $k==9 || $k==3))
                $typeId = 3;
            if($k==15)
                $typeId = 4;
            $this->insert('event_category_type', ['category_id'=>$k+1, 'type_id'=>$typeId]);
        }

        $this->createTable('{{%event_question}}', [
            'id' => Schema::TYPE_PK,
            'type_id' =>Schema::TYPE_INTEGER . ' NOT NULL',
            'question' => Schema::TYPE_STRING . '(255) NOT NULL',
            'tooltip' => Schema::TYPE_STRING . '(255) DEFAULT NULL',
        ], $tableOptions);
        $this->addForeignKey('fk_event_question_type', 'event_question', 'type_id', 'event_type', 'id', 'CASCADE', 'CASCADE');

        $questions = ['Куда пойдем?', 'Что входит в стоимость?', 'Что взять с собой?', 'Почему сюда стоит сходить?', 'Дополнительно'];
        foreach($questions as $q){
            $this->insert('event_question', [
                'type_id' => 1,
                'question' => $q,
            ]);
            $this->insert('event_question', [
                'type_id' => 2,
                'question' => $q,
            ]);
        }
        $questions = ['Что будем делать?', 'Что входит в стоимость?', 'Что взять с собой?', 'Почему сюда стоит сходить?', 'Дополнительно'];
        foreach($questions as $q){
            $this->insert('event_question', [
                'type_id' => 3,
                'question' => $q,
            ]);
        }
        $this->insert('event_question', [
            'type_id' => 4,
            'question' => 'Описание',
        ]);

        $this->createTable('{{%event_description}}', [
            'id' => Schema::TYPE_PK,
            'event_id' =>Schema::TYPE_INTEGER . ' NOT NULL',
            'question_id' =>Schema::TYPE_INTEGER . ' NOT NULL',
            'lang_id' => Schema::TYPE_INTEGER . '(255) NOT NULL',
            'description' => Schema::TYPE_STRING . '(255) NOT NULL',
        ], $tableOptions);
        $this->addForeignKey('fk_event_description_event', 'event_description', 'event_id', 'event', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_event_description_question', 'event_description', 'question_id', 'event_question', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_event_description_lang', 'event_description', 'lang_id', 'lang', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%event_date}}', [
            'id' => Schema::TYPE_PK,
            'event_id' =>Schema::TYPE_INTEGER . ' NOT NULL',
            'date_start' =>Schema::TYPE_TIMESTAMP . ' NOT NULL',
        ], $tableOptions . ' COMMENT "Конкретная дата события"');
        $this->addForeignKey('fk_event_date_event', 'event_date', 'event_id', 'event', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%event_date_time}}', [
            'id' => Schema::TYPE_PK,
            'date_id' =>Schema::TYPE_INTEGER . ' NOT NULL',
            'time' =>Schema::TYPE_TIME . ' NOT NULL',
        ], $tableOptions);
        $this->addForeignKey('fk_event_date_time_date', 'event_date_time', 'date_id', 'event_date', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%event_time_repeat}}', [
            'id' => Schema::TYPE_PK,
            'event_id' =>Schema::TYPE_INTEGER . ' NOT NULL',
            'dayweek' => 'ENUM("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday")',
            'time' =>Schema::TYPE_TIME . ' NOT NULL',
        ], $tableOptions . ' COMMENT "Время для повторяющихся событий"');
        $this->addForeignKey('fk_event_time_repeat_event', 'event_time_repeat', 'event_id', 'event', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%event_cost}}', [
            'id' => Schema::TYPE_PK,
            'event_id' =>Schema::TYPE_INTEGER . ' NOT NULL',
            'amount' => Schema::TYPE_FLOAT . ' NOT NULL',
            'people_min' =>Schema::TYPE_INTEGER . ' NOT NULL',
            'people_max' =>Schema::TYPE_INTEGER,
        ], $tableOptions . ' COMMENT "Стоимость события"');
    }

    public function down()
    {
        echo "m150216_053501_event cannot be reverted.\n";

        return false;
    }
}
