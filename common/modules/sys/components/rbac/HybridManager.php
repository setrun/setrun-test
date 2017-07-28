<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

namespace sys\components\rbac;

use yii\db\Query;
use yii\di\Instance;
use yii\db\Connection;
use yii\rbac\PhpManager;

/**
 * Class HybridManager.
 */
class HybridManager extends PhpManager
{
    /**
     * @var string Name of table
     */
    public $table = '{{%auth_assignment}}';

    /**
     * @var Connection
     */
     public $db = 'db';

    /**
     * Initializes the application component.
     * This method overrides the parent implementation by establishing the database connection.
     */
    public function init()
    {
        $this->db = Instance::ensure($this->db, Connection::className());
        parent::init();
    }

    /**
     * @inheritdoc
     */
   protected function loadFromFile($file) : array
   {
      if ($this->assignmentFile == $file) {
          $query = (new Query)->select('*')->from($this->table);
          $assignments = [];
          foreach ($query->all($this->db) as $row) {
              $assignments[$row['user_id']] = json_decode($row['assignments'], true);
          }
          return $assignments;
      } else {
          return parent::loadFromFile($file);
      }
   }

    /**
     * @inheritdoc
     */
   public function saveToFile($data, $file) : void
   {
       if ($this->assignmentFile == $file) {
           $transaction = $this->db->beginTransaction();
           try {
               foreach ($data as $key => $value) {
                   $this->db->createCommand()
                       ->delete($this->table, ['user_id' => $key])
                       ->execute();
                   $this->db->createCommand()
                       ->insert($this->table, [
                           'user_id'     => $key,
                           'assignments' => json_encode($value),
                           'updated_at'  => time()
                       ])
                       ->execute();
               }
               $transaction->commit();
           } catch(\Exception $e) {
               $transaction->rollBack();
               throw $e;
           }
       } else {
           parent::saveToFile($data, $file);
       }
   }
}