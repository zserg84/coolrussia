<?php

use yii\db\Schema;
use yii\db\Migration;

class m150213_071004_geo_ip extends Migration
{
    public function up()
    {
        $tableOptions = ($this->db->driverName === 'mysql') ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=MyISAM' : null;

        $this->createTable('geo_ip_country_world', array(
            'id'=>'pk',
            'country_id'=>"int(11) DEFAULT '0'",
            'begin_ip'=>"bigint(11) DEFAULT NULL",
            'end_ip'=>"bigint(11) DEFAULT '0'",
        ), $tableOptions);
        $this->createIndex('country_id', 'geo_ip_country_world', 'country_id');
        $this->createIndex('begin_ip', 'geo_ip_country_world', 'begin_ip');

        $this->createTable('geo_ip_country_europe', array(
            'id'=>'pk',
            'country_id'=>"int(11) DEFAULT '0'",
            'begin_ip'=>"bigint(11) DEFAULT NULL",
            'end_ip'=>"bigint(11) DEFAULT '0'",
        ), $tableOptions);
        $this->createIndex('country_id', 'geo_ip_country_europe', 'country_id');
        $this->createIndex('begin_ip', 'geo_ip_country_europe', 'begin_ip');

        $file = __DIR__.'/sql/geo_ip.sql';
        $content = file_get_contents($file);
        $contentArr = explode(';', $content);
        foreach ($contentArr as $query) {
            if ($query = trim($query)) {
                $this->execute($query.';');
            }
        }
    }

    public function down()
    {
        $this->dropTable('geo_ip_country_world');
        $this->dropTable('geo_ip_country_europe');
    }
}
