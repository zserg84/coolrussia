<?php

use yii\db\Schema;
use yii\db\Migration;

class m150305_060831_recommended_event extends Migration
{
    public function up()
    {
        $this->addColumn('event', 'recommended', Schema::TYPE_BOOLEAN . ' COMMENT "Рекомендуемые"');
        $this->createIndex('idx_event_recommended', 'event', 'recommended');
    }

    public function down()
    {
        $this->dropColumn('event', 'recommended');
    }
}
