<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=setrun',
    'username' => 'root',
    'password' => '',
    'charset'  => 'utf8mb4',
    'tablePrefix' => 'setrun_',
    'schemaCache'         => 'cache',
    'enableSchemaCache'   => true,
    'schemaCacheDuration' => 3600
];