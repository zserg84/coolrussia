<?php

use yii\db\Schema;
use yii\db\Migration;

class m150513_075139_settings_region extends Migration
{
    public function up()
    {
        $this->createTable('contract_settings_region', [
            'id' => 'pk',
            'setting_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'country_id' => Schema::TYPE_INTEGER ,
            'state_id' => Schema::TYPE_INTEGER,
            'city_id' => Schema::TYPE_INTEGER,
        ]);
        $this->addForeignKey('fk_contract_settings_region_setting', 'contract_settings_region', 'setting_id', 'contract_settings', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_contract_settings_region_country', 'contract_settings_region', 'country_id', 'country', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_contract_settings_region_state', 'contract_settings_region', 'state_id', 'state', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_contract_settings_region_city', 'contract_settings_region', 'city_id', 'city', 'id', 'CASCADE', 'CASCADE');

    }

    public function down()
    {
        echo "m150513_075139_settings_region cannot be reverted.\n";

    }
    
    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }
    
    public function safeDown()
    {
    }
    */
}
