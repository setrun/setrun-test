<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

$configurator = Yii::$container->get(\sys\interfaces\ConfiguratorInterface::class);
$params = array_merge(
    require(__DIR__ . '/../../../common/config/params.php'),
    require(__DIR__ . '/../../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);
return [
    'bootstrap' => ['log', 'queue'],
    'id' => 'app-master',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'app\controllers',
    'components' => [
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class'   => 'yii\log\FileTarget',
                    'levels'  => ['error'],
                    'logFile' => '@runtime/logs/web-error.log',
                    'logVars' => ['_GET', '_POST', '_FILES', '_COOKIE', '_SESSION']
                ],
                [
                    'class'   => 'yii\log\FileTarget',
                    'levels'  => ['warning'],
                    'logFile' => '@runtime/logs/web-warning.log',
                    'logVars' => ['_GET', '_POST', '_FILES', '_COOKIE', '_SESSION']
                ],
            ],
        ],
        'view' => [
            'theme' => [
                'basePath' => '@themes/' . $configurator->component('sys.theme'),
            ],
        ],
        'queue' => [
            'class' => \yii\queue\db\Queue::class,
            'mutex' => \yii\mutex\MysqlMutex::class
        ],
    ],
    'params' => $params,
];