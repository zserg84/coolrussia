<?php

use yii\db\Schema;
use yii\db\Migration;

class m150216_071140_create_image_upd_geo extends Migration
{
    public function up()
    {
        $this->createTable('{{%image}}', array(
            'id'=>'pk',
            'ext'=>'varchar(4)',
            'comment'=>'varchar(255)',
            'create_time'=>'int(11)',
            'sort'=>'int(10)',
        ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');

        $this->addColumn('{{%users}}', 'image_id', 'int(11) DEFAULT NULL');
        $this->addForeignKey('fk__users__image_id', '{{%users}}', 'image_id', '{{%image}}', 'id', 'SET NULL', 'CASCADE');

        $this->alterColumn('{{%geo_city}}', 'region_id', 'int(11) DEFAULT NULL');
    }

    public function down()
    {
//        $this->alterColumn('{{%geo_city}}', 'region_id', 'int(11) NOT NULL');

        $this->dropForeignKey('fk__users__image_id', '{{%users}}');
        $this->dropColumn('{{%users}}', 'image_id');

        $this->dropTable('{{%image}}');
    }
}
