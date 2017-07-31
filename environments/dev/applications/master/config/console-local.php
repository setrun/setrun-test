<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

return [
    'bootstrap' => ['gii'],
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'components' => [
        'db' => require __DIR__ . '/db-local.php',
    ]
];