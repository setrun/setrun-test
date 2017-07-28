<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

return [
    'bootstrap' => ['sys\components\Bootstrap'],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf',
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => '_session',
        ],
        'authManager' => [
            'class' => 'sys\components\rbac\HybridManager'
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules' => [
                "<_a:(login|logout)>" => "sys/user/auth/<_a>",
            ],
        ],
        'user' => [
            'identityClass' => 'sys\components\user\Identity',
            'enableAutoLogin' => true,
            'loginUrl' => ['sys/user/auth/login'],
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
    ]
];