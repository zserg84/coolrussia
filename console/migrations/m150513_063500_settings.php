<?php

use yii\db\Schema;
use yii\db\Migration;

class m150513_063500_settings extends Migration
{
    public function up()
    {
        $this->dropTable('contract_settings');
        $this->createTable('contract_settings', [
            'id' => 'pk',
            'contract_id' => Schema::TYPE_INTEGER .' NOT NULL',
            'notification_of_permormer_response' => Schema::TYPE_BOOLEAN . ' COMMENT "Отправлять уведомления на E-mail об откликах Исполнителей на заказ" ',
            'apply_filter_territory' => Schema::TYPE_BOOLEAN . ' COMMENT " Применить при входе в Систему территориальный фильтр в соответствии с моим территориальным месторасположением" ',
            'notification_of_new_orders' => Schema::TYPE_BOOLEAN . ' COMMENT "Отправлять уведомления на E-mail о новых заказах в системе в соответствии с направлениями деятельности Вашей компании" ',
            'notification_of_orders_company_performer' => Schema::TYPE_BOOLEAN . ' COMMENT "Отправлять уведомления на E-mail о заказах по которым Ваша компания выбрана исполнителем" ',
            'is_dop_regions' => Schema::TYPE_BOOLEAN . ' COMMENT "Кроме региона по умолчанию, дополнительно отображать заказы по следующим регионам" ',
        ]);
        $this->addForeignKey('fk_contract_settings_contract', 'contract_settings', 'contract_id', 'contract', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        echo "m150513_063500_settings cannot be reverted.\n";

//        return false;
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
