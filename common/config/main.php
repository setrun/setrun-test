<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

return [
    'bootstrap' => ['common\components\Bootstrap'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset'
    ],
    'language'   => 'ru_Ru',
    'vendorPath' => dirname(dirname(__DIR__) ). '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'config' => 'sys\interfaces\ConfiguratorInterface',
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
            ]
        ],
    ]
];
