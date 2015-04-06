<?php

use yii\db\Schema;
use yii\db\Migration;

class m150202_050134_create_blog_user_comments extends Migration
{
    public function up()
    {
        $file = __DIR__.'/sql/user_blog_comments.sql';
        $content = file_get_contents($file);
        $this->execute($content);
    }

    public function down()
    {
        $this->dropForeignKey('FK_comment_parent', 'comments');
        $this->dropForeignKey('FK_comment_model_class', 'comments');
        $this->dropForeignKey('FK_comment_author', 'comments');
        $this->dropTable('comments');

        $this->dropForeignKey('FK_user_email_user', 'user_email');
        $this->dropTable('user_email');

        $this->dropTable('comments_models');

        $this->dropForeignKey('FK_profile_user', 'profiles');
        $this->dropTable('profiles');

        $this->dropTable('blogs');

        $this->dropTable('users');
    }
}
