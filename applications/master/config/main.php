<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

$params = array_merge(
    require(ROOT_DIR . '/common/config/params.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);
return [
    'basePath' => dirname(__DIR__),
    'components' => [
        'db' => require __DIR__ . '/db-local.php',
    ],
    'params' => $params,
];