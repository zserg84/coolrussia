<?php

use yii\db\Schema;
use yii\db\Migration;

class m150303_100559_create_event_images extends Migration
{
    public function up()
    {
        $this->addColumn('{{%event}}', 'image_id', 'int(11) DEFAULT NULL');
        $this->addForeignKey('fk__event__image_id', '{{%event}}', 'image_id', '{{%image}}', 'id', 'SET NULL', 'CASCADE');

        $this->createTable('{{%event_image}}', [
            'id' => 'pk',
            'event_id' => 'int(11) NOT NULL',
            'image_id' => 'int(11) NOT NULL',
        ]);
        $this->addForeignKey('fk__event_image__event_id', '{{%event_image}}', 'event_id', '{{%event}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk__event_image__image_id', '{{%event_image}}', 'image_id', '{{%image}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk__event_image__image_id', '{{%event_image}}');
        $this->dropForeignKey('fk__event_image__event_id', '{{%event_image}}');
        $this->dropTable('{{%event_image}}');

        $this->dropForeignKey('fk__event__image_id', '{{%event}}');
        $this->dropColumn('{{%event}}', 'image_id');
    }
}
