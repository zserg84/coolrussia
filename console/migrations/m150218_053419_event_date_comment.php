<?php

use yii\db\Schema;
use yii\db\Migration;

class m150218_053419_event_date_comment extends Migration
{
    public function up()
    {
        $this->addColumn('event', 'date_comment', 'VARCHAR(255) COMMENT "Комментарий к дате события, если выбрана свободная дата"');
    }

    public function down()
    {
        $this->dropColumn('event', 'date_comment');
    }
}
