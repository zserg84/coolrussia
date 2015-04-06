<?php

use yii\db\Schema;
use yii\db\Migration;

class m150206_084510_add_geo extends Migration
{
    public function up()
    {
        $tableOptions = ($this->db->driverName === 'mysql') ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' : null;

        $this->createTable("geo_country", array(
            "id"=>"int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT",
            "name_ru"=>"varchar(50) NOT NULL",
            "name_en"=>"varchar(50) NOT NULL",
            "code"=>"varchar(5) NOT NULL",
            "sort"=>"int(11) NOT NULL",
        ), $tableOptions);


        $this->createTable("geo_region", array(
            "id"=>"int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT",
            "country_id"=>"int(11) NOT NULL",
            "name_ru"=>"varchar(50) NOT NULL",
            "name_en"=>"varchar(50) NOT NULL",
            "sort"=>"int(11) NOT NULL",
        ), $tableOptions);


        $this->createTable("geo_city", array(
            "id"=>"int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT",
            "country_id"=>"int(11) NOT NULL",
            "region_id"=>"int(11) NOT NULL",
            "name_ru"=>"varchar(50) NOT NULL",
            "name_en"=>"varchar(50) NOT NULL",
            "sort"=>"int(11) NOT NULL DEFAULT '0'",
            "confirm"=>"tinyint(1) NOT NULL DEFAULT '0'",
        ), $tableOptions);


        $file = __DIR__.'/sql/geo.sql';
        $content = file_get_contents($file);
        $this->execute($content);

        $this->update('geo_city', ['confirm'=>1]);

        $this->addForeignKey('fk__geo_region__country_id', 'geo_region', 'country_id', 'geo_country', 'id', null, null);
        $this->addForeignKey('fk__geo_city__country_id', 'geo_city', 'country_id', 'geo_country', 'id', null, null);
        $this->addForeignKey('fk__geo_city__region_id', 'geo_city', 'region_id', 'geo_region', 'id', null, null);
    }

    public function down()
    {
        $this->dropForeignKey('fk__geo_city__region_id', 'geo_city');
        $this->dropForeignKey('fk__geo_city__country_id', 'geo_city');
        $this->dropForeignKey('fk__geo_region__country_id', 'geo_region');

        $this->dropTable('geo_city');
        $this->dropTable('geo_region');
        $this->dropTable('geo_country');
    }
}
