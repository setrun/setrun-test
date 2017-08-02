<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

namespace sys\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `sys_setting`.
 */
class m170731_105256_create_sys_setting_table extends Migration
{
    /**
     * @var string Name of create a table
     */
    private $table = '{{%sys_setting}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';
        }
        $this->createTable($this->table, [
            'id'         => $this->primaryKey(),
            'user_id'    => $this->integer()->defaultValue(null),
            'did'        => $this->integer()->defaultValue(null),
            'name'       => $this->string(64)->notNull(),
            'json_value' => $this->text(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull()
        ], $tableOptions);

        $this->createIndex(  '{{%idx-sys_setting-name}}',  $this->table, 'name');

        $this->addForeignKey('{{%fk-sys_setting-user}}',   $this->table, 'user_id', '{{%sys_user}}',   'id', 'CASCADE');
        $this->addForeignKey('{{%fk-sys_setting-domain}}', $this->table, 'did',     '{{%sys_domain}}', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable($this->table);
    }
}
