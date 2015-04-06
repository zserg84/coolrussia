<?php

use yii\db\Schema;
use yii\db\Migration;

class m150212_115955_lang_upd extends Migration
{
    public function up()
    {
        $this->update('lang', ['local'=>'en-US'], 'local=:local', ['local'=>'en-EN']);
    }

    public function down()
    {
        $this->update('lang', ['local'=>'en-EN'], 'local=:local', ['local'=>'en-US']);
    }
}
