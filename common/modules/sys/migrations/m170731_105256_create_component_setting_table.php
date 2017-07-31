<?php

use yii\db\Migration;

/**
 * Handles the creation of table `component_setting`.
 */
class m170731_105256_create_component_setting_table extends Migration
{
    /**
     * @var string Name of create a table
     */
    private $table = '{{%component_setting}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->table, [
            'id'         => $this->primaryKey(),
            'name'       => $this->string(64)->notNull(),
            'json_value' => $this->text(),
            'user_id'    => $this->integer()->defaultValue(null),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull()
        ]);

        $this->createIndex('{{%idx-component_setting-name}}',  $this->table, 'name');
        $this->addForeignKey('{{%fk-component_setting-user}}', $this->table, 'user_id', '{{%user}}', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable($this->table);
    }
}
