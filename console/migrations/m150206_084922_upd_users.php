<?php

use yii\db\Schema;
use yii\db\Migration;

class m150206_084922_upd_users extends Migration
{
    public function up()
    {
        $tableOptions = ($this->db->driverName === 'mysql') ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' : null;

        $this->dropIndex('username', '{{%users}}');
        $this->renameColumn('{{%users}}', 'username', 'name');
        $this->alterColumn('{{%users}}', 'name', 'varchar(100) NOT NULL');
        $this->addColumn('{{%users}}', 'city_id', 'int(11) DEFAULT NULL');
        $this->addColumn('{{%users}}', 'birthday', 'date');
        $this->addColumn('{{%users}}', 'rate', "FLOAT(10.2) NOT NULL DEFAULT '0'");
        $this->addForeignKey('fk__users__city_id', '{{%users}}', 'city_id', 'geo_city', 'id', 'SET NULL', 'CASCADE');

        $this->createTable('{{%profile}}', array(
            'id'=>'pk',
            'user_id'=>'int(11) NOT NULL',
            'lang_id'=>'int(11) NOT NULL',
            'name'=>'varchar(100) NOT NULL',
            'about'=>'text',
            'video'=>'varchar(255)',
        ), $tableOptions);

        $lang_id = $this->db->createCommand('SELECT `id` FROM {{%lang}} ORDER BY `default` DESC LIMIT 1')->queryScalar();
        $profile_list = $this->db->createCommand('SELECT * FROM {{%profiles}} ORDER BY `user_id`')->queryAll();
        foreach ($profile_list as $row) {
            $data = ['lang_id'=>$lang_id];
            $data['user_id'] = $row['user_id'];
            $data['name'] = $row['name'];
            $this->insert('{{%profile}}', $data);
        }

        $this->addForeignKey('fk__profile__user_id', '{{%profile}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk__profile__lang_id', '{{%profile}}', 'lang_id', '{{%lang}}', 'id', 'CASCADE', 'CASCADE');

        $this->dropTable('{{%profiles}}');
    }

    public function down()
    {
        $tableOptions = ($this->db->driverName === 'mysql') ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' : null;

        $this->createTable('{{%profiles}}', array(
            'user_id'=>'int(11)',
            'name'=>'varchar(50)',
            'surname'=>'varchar(50)',
            'avatar_url'=>'varchar(64)',
        ), $tableOptions);

        $profile_list = $this->db->createCommand('SELECT * FROM {{%profile}} WHERE `lang_id` = (SELECT `id` FROM {{%lang}} ORDER BY `default` DESC LIMIT 1) ORDER BY `user_id`')->queryAll();
        foreach ($profile_list as $row) {
            $data['user_id'] = $row['user_id'];
            $data['name'] = $row['name'];
            $this->insert('{{%profiles}}', $data);
        }

        $this->addPrimaryKey('user_id', '{{%profiles}}', 'user_id');
        $this->addForeignKey('FK_profile_user', '{{%profiles}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');

        $this->dropTable('{{%profile}}');

        $this->dropForeignKey('fk__users__city_id', '{{%users}}');
        $this->dropColumn('{{%users}}', 'rate');
        $this->dropColumn('{{%users}}', 'birthday');
        $this->dropColumn('{{%users}}', 'city_id');
        $this->alterColumn('{{%users}}', 'name', 'varchar(30) NOT NULL');
        $this->renameColumn('{{%users}}', 'name', 'username');
        $this->createIndex('username', '{{%users}}', ['username'], true);
    }
}
