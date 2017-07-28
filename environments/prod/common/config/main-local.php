<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=setrun',
            'username' => 'root',
            'password' => '',
            'charset'  => 'utf8mb4',
            'tablePrefix' => 'setrun_',
            'schemaCache'         => 'cache',
            'enableSchemaCache'   => true,
            'schemaCacheDuration' => 3600
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
];
