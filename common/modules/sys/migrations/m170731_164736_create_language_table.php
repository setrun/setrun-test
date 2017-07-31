<?php

use yii\db\Migration;

/**
 * Handles the creation of table `language`.
 */
class m170731_164736_create_language_table extends Migration
{
    /**
     * @var string Name of create a table
     */
    private $table = '{{%language}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable($this->table, [
            'id'          => $this->primaryKey(),
            'did'         => $this->integer()->defaultValue(null),
            'slug'        => $this->string(50)->notNull(),
            'name'        => $this->string(50)->notNull(),
            'locale'      => $this->string(255)->notNull(),
            'alias'       => $this->string(50)->notNull(),
            'icon_id'     => $this->string(10),
            'bydefault'   => $this->integer()->notNull()->defaultValue(0),
            'status'      => $this->smallInteger()->notNull()->defaultValue(1),
            'position'    => $this->integer()->notNull()->defaultValue(1),
            'created_at'  => $this->integer()->notNull(),
            'updated_at'  => $this->integer()->notNull()
        ], $tableOptions);

        $this->createIndex('{{%idx-language-name}}',   $this->table, 'name');
        $this->createIndex('{{%idx-language-slug}}',   $this->table, 'slug');
        $this->createIndex('{{%idx-language-alias}}',  $this->table, 'alias');
        $this->createIndex('{{%idx-language-status}}', $this->table, 'status');

        $this->addForeignKey('{{%fk-language-domain}}', $this->table, 'did',     '{{%domain}}', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable($this->table);
    }
}
