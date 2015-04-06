<?php

use yii\db\Schema;
use yii\db\Migration;

class m150227_114836_upd_event extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%event_date}}', 'date_start', 'int(11) NOT NULL');

        $this->addColumn('{{%event}}', 'user_id', 'INT(11) NOT NULL AFTER `id`');

        if (intval($this->db->createCommand('SELECT COUNT(*) FROM event')->queryScalar())) {
            $user_id = $this->db->createCommand("SELECT id FROM users ORDER BY id ASC LIMIT 1")->queryScalar();
            if ($user_id) {
                $this->db->createCommand("UPDATE event SET user_id = {$user_id}")->execute();
            }
        }
        $this->addForeignKey('fk__event__user_id', '{{%event}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');

        $this->addColumn('{{%event}}', 'city_id', 'int(11) DEFAULT NULL');
        $this->addForeignKey('fk__event__city_id', '{{%event}}', 'city_id', 'geo_city', 'id', 'SET NULL', 'CASCADE');

        $this->addColumn('{{%event}}', 'status', 'tinyint(2) NOT NULL DEFAULT 0');
    }

    public function down()
    {
        $this->dropColumn('{{%event}}', 'status');

        $this->dropForeignKey('fk__event__city_id', '{{%event}}');
        $this->dropColumn('{{%event}}', 'city_id');

        $this->dropForeignKey('fk__event__user_id', '{{%event}}');
        $this->dropColumn('{{%event}}', 'user_id');

        $this->alterColumn('{{%event_date}}', 'date_start', 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
    }
}
