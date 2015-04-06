<?php

use yii\db\Schema;
use yii\db\Migration;

class m150305_101924_event_cost_alter extends Migration
{
    public function up()
    {
        $this->addForeignKey('fk_event_cost_event', 'event_cost', 'event_id', 'event', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx_event_cost_amount', 'event_cost', 'amount');
        $this->createIndex('idx_event_cost_people_min', 'event_cost', 'people_min');
        $this->createIndex('idx_event_cost_people_max', 'event_cost', 'people_max');
    }

    public function down()
    {

    }
}
