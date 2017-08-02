<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

namespace sys\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `sys_domain`.
 */
class m170731_104122_create_sys_domain_table extends Migration
{
    /**
     * @var string Name of create a table
     */
    private $table = '{{%sys_domain}}';

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
            'domain'     => $this->string(100)->notNull(),
            'name'       => $this->string(100)->notNull(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull()
        ], $tableOptions);

        $this->createIndex('{{%idx-sys_domain-name}}',   $this->table, 'name');
        $this->createIndex('{{%idx-sys_domain-domain}}', $this->table, 'domain');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable($this->table);
    }
}
