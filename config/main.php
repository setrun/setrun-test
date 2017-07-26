<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset'
    ],
    'vendorPath' => dirname(__DIR__) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager'
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules' => [

            ],
        ],
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
        'i18n' => [
            'translations' => [
                'sys*' => [
                    'class'    => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@sys/messages',
                    'fileMap' => [
                        'sys' => 'sys.php',
                    ]
                ]
            ]
        ],
    ],
    'modules' => [
        'sys' => [
            'class' => 'sys\Module'
        ]
    ],
];
