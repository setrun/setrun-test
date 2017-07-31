<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

$configurator = Yii::$container->get(\sys\interfaces\ConfiguratorInterface::class);
$slug = $configurator->component('sys.backend.slug', 'adm');

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
            'rules' => [
                "<_a:(login|logout)>" => "sys/user/auth/<_a>",
                "{$slug}" => "sys/backend/backend/index",
                "{$slug}/<_m:\w+>/<_c:\w+(-\w+)*>" => "<_m>/backend/<_c>/index",
                "{$slug}/<_m:\w+>/<_c:[-\w]+>/<_a:[-\w]+>/<id:\d+>" => "<_m>/backend/<_c>/<_a>",
                "{$slug}/<_m:\w+>/<_c:[-\w]+>/<_a:[-\w]+>" => "<_m>/backend/<_c>/<_a>"
            ]
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