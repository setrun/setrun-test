<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

namespace sys\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `sys_user`.
 */
class m170726_140445_create_sys_user_table extends Migration
{
    /**
     * @var string Name of create a table
     */
    private $table = '{{%sys_user}}';

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';
        }

        $this->createTable($this->table, [
            'id'                   => $this->primaryKey(),
            'username'             => $this->string(),
            'auth_key'             => $this->string(32)->notNull(),
            'password_hash'        => $this->string(),
            'password_reset_token' => $this->string()->unique(),
            'email'                => $this->string(),
            'email_confirm_token'  => $this->string()->unique(),
            'status'               => $this->smallInteger()->notNull()->defaultValue(0),
            'role'                 => $this->text(),
            'created_at'           => $this->integer()->unsigned()->notNull(),
            'updated_at'           => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-sys_user-username}}', $this->table, 'username');
        $this->createIndex('{{%idx-sys_user-email}}',    $this->table, 'email');
        $this->createIndex('{{%idx-sys_user-status}}',   $this->table, 'status');
    }

    public function down()
    {
        $this->dropTable($this->table);
    }
}
