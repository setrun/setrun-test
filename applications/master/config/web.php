<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */
$configurator = Yii::$container->get(\sys\components\Configurator::class);
return  [
    'id' => 'app-master',
    'controllerNamespace' => 'app\controllers',
    'components' => [
        'view' => [
            'theme' => [
                'basePath' => '@themes/' . $configurator->component('sys.theme'),
            ]
        ],
        'assetManager' => [
            'forceCopy' => $configurator->component('sys.assets.forcedCopy', false)
        ]
    ]
];
