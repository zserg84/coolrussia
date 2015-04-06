<?php

use yii\db\Schema;
use yii\db\Migration;

class m150213_074000_faq_update extends Migration
{
    public function up()
    {
        $this->addColumn('faq', 'status', 'BOOL DEFAULT 0 COMMENT "Показать/скрыть"');
    }

    public function down()
    {
        $this->dropColumn('faq', 'status');
    }
}
