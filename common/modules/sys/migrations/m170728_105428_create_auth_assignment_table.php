<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

use yii\db\Migration;

/**
 * Handles the creation of table `auth_assignment`.
 */
class m170728_105428_create_auth_assignment_table extends Migration
{
    /**
     * @var string Name of create a table
     */
    private $table = '{{%auth_assignment}}';

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
            'user_id'     => $this->integer()->unique(),
            'assignments' => $this->text(),
            'updated_at'  => $this->integer()->unsigned()->notNull()
        ], $tableOptions);

        $this->createIndex(  '{{%idx-auth_assignment-user_id}}',  $this->table, 'user_id');
        $this->addForeignKey('{{%fk-auth_assignment-user}}', $this->table, 'user_id', '{{%user}}', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable($this->table);
    }
}
