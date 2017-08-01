<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

return [
    'bootstrap' => ['sys\components\Bootstrap'],
    'components' => [
        'authManager' => [
            'class' => 'sys\components\rbac\HybridManager',
        ],
        'i18n' => [
            'translations' => [
                'sys*' => [
                    'class'    => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@sys/messages',
                    'fileMap' => [
                        'sys'      => 'sys.php',
                        'sys/user' => 'user.php',
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
    'controllerMap' => [
        'migrate' => [
            'migrationNamespaces' => [
                'sys\migrations'
            ],
        ]
    ],
];