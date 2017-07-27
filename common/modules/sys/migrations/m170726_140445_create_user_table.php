<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m170726_140445_create_user_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id'                   => $this->primaryKey(),
            'username'             => $this->string(),
            'auth_key'             => $this->string(32)->notNull(),
            'password_hash'        => $this->string(),
            'password_reset_token' => $this->string()->unique(),
            'email'                => $this->string(),
            'email_confirm_token'  => $this->string()->unique(),
            'status'               => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at'           => $this->integer()->unsigned()->notNull(),
            'updated_at'           => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-user-username', '{{%user}}', 'username');
        $this->createIndex('idx-user-email',    '{{%user}}', 'email');
        $this->createIndex('idx-user-status',   '{{%user}}', 'status');
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
