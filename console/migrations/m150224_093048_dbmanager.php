<?php

use yii\db\Migration;

class m150224_093048_dbmanager extends Migration
{
    public function up()
    {
        $tableOptions = ($this->db->driverName === 'mysql') ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' : null;

        $this->createTable('{{%auth_rule}}', array(
            'name'=>'varchar(64) not null',
            'data'=>'text',
            'created_at'=>'integer',
            'updated_at'=>'integer',
        ), $tableOptions);
        $this->addPrimaryKey('name', '{{%auth_rule}}', 'name');

        $this->createTable('{{%auth_item}}', array(
            'name'=>'varchar(64) not null',
            'type'=>'integer not null',
            'description'=>'text',
            'rule_name'=>'varchar(64)',
            'data'=>'text',
            'created_at'=>'integer',
            'updated_at'=>'integer',
        ), $tableOptions);
        $this->addPrimaryKey('name', '{{%auth_item}}', 'name');
        $this->addForeignKey('fk__auth_item__rule_name', '{{%auth_item}}', 'rule_name', '{{%auth_rule}}', 'name', 'SET NULL', 'CASCADE');
        $this->createIndex('type', '{{%auth_item}}', 'type');

        $this->createTable('{{%auth_item_child}}', array(
            'parent'=>'varchar(64) not null',
            'child'=>'varchar(64) not null',
        ), $tableOptions);
        $this->addPrimaryKey('parent_child', '{{%auth_item_child}}', ['parent', 'child']);
        $this->addForeignKey('fk__auth_item_child__parent', '{{%auth_item_child}}', 'parent', '{{%auth_item}}', 'name', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk__auth_item_child__child', '{{%auth_item_child}}', 'child', '{{%auth_item}}', 'name', 'CASCADE', 'CASCADE');

        $this->createTable('{{%auth_assignment}}', array(
            'item_name'=>'varchar(64) not null',
            'user_id'=>'varchar(64) not null',
            'created_at'=>'integer',
        ), $tableOptions);
        $this->addPrimaryKey('item_name__user_id', '{{%auth_assignment}}', ['item_name', 'user_id']);
        $this->addForeignKey('fk__auth_assignment__item_name', '{{%auth_assignment}}', 'item_name', '{{%auth_item}}', 'name', 'CASCADE', 'CASCADE');

        echo "\r\n--IMPORTANT!---------------------------------------\r\n---\r\n use 'php yii rbac/export'\r\n---\r\n";
    }


    public function down()
    {
        $this->dropForeignKey('fk__auth_assignment__item_name', '{{%auth_assignment}}');
        $this->dropTable('{{%auth_assignment}}');

        $this->dropForeignKey('fk__auth_item_child__child', '{{%auth_item_child}}');
        $this->dropForeignKey('fk__auth_item_child__parent', '{{%auth_item_child}}');
        $this->dropTable('{{%auth_item_child}}');

        $this->dropForeignKey('fk__auth_item__rule_name', '{{%auth_item}}');
        $this->dropTable('{{%auth_item}}');

        $this->dropTable('{{%auth_rule}}');
    }
}
