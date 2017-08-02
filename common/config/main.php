<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

use yii\i18n\PhpMessageSource;

return [
    'bootstrap' => ['log', 'queue'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset'
    ],
    'vendorPath' => ROOT_DIR . '/vendor',
    'components' => [
        'authManager' => [
            'class' => 'sys\components\rbac\HybridManager'
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'config' => [
            'class' => 'sys\components\Configurator'
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class'   => 'yii\log\FileTarget',
                    'levels'  => ['error'],
                    'logFile' => '@runtime/logs/web-error.log',
                    'logVars' => ['_GET', '_COOKIE', '_SESSION']
                ],
                [
                    'class'   => 'yii\log\FileTarget',
                    'levels'  => ['warning'],
                    'logFile' => '@runtime/logs/web-warning.log',
                    'logVars' => ['_GET', '_COOKIE', '_SESSION']
                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                'sys*' => [
                    'class'    => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@sys/messages',
                    'fileMap' => [
                        'sys'      => 'sys.php',
                        'sys/user' => 'user.php'
                    ]
                ]
            ]
        ],
        'queue' => [
            'class'  => \yii\queue\db\Queue::class,
            'mutex'  => \yii\mutex\MysqlMutex::class,
            'as log' => \yii\queue\LogBehavior::class
        ],
        'mailer' => [
            'class'    => 'yii\swiftmailer\Mailer',
            'viewPath' => '@app/views/mail'
        ],
    ],
    'modules' => [
        'sys' => [
            'class' => 'sys\Module'
        ]
    ]
];
