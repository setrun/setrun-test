<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

$params = require __DIR__ . '/params.php';
$slug   = $params['backendSlug'];
return [
    'bootstrap' => ['common\components\Bootstrap'],
    'components' => [
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => '_session',
        ],
        'user' => [
            'identityClass' => 'sys\components\user\Identity',
            'enableAutoLogin' => true,
            'loginUrl' => ['sys/user/auth/login'],
        ],
        'view' => [
            'theme' => [
                'class' => 'sys\components\base\Theme'
            ]
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'suffix'          => null,
            'normalizer' => [
                'class' => 'yii\web\UrlNormalizer',
                'action' => \yii\web\UrlNormalizer::ACTION_REDIRECT_TEMPORARY
            ],
            'rules' => [
                "<_a:(login|logout)>" => "sys/user/auth/<_a>",
                "{$slug}" => "sys/backend/backend/index",
                "{$slug}/<_m:\w+>/<_c:\w+(-\w+)*>" => "<_m>/backend/<_c>/index",
                "{$slug}/<_m:\w+>/<_c:[-\w]+>/<_a:[-\w]+>/<id:\d+>" => "<_m>/backend/<_c>/<_a>",
                "{$slug}/<_m:\w+>/<_c:[-\w]+>/<_a:[-\w]+>" => "<_m>/backend/<_c>/<_a>"
            ]
        ]
    ]
];