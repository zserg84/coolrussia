<?php

use yii\db\Schema;
use yii\db\Migration;

class m150217_115736_delete_event_date_time extends Migration
{
    public function up()
    {
        $this->dropTable('event_date_time');
    }

    public function down()
    {

    }
}
