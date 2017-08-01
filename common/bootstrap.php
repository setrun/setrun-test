<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

// Aliases

Yii::setAlias('@common', dirname(__DIR__) . '/common');
Yii::setAlias('@themes', dirname(__DIR__) . '/themes');
Yii::setAlias('@root',   dirname(__DIR__) . '');
Yii::setAlias('@sys',    dirname(__DIR__) . '/common/modules/sys');

// DI
\Yii::$container->setSingleton(\sys\components\Configurator::class);
\Yii::$container->setSingleton(\sys\interfaces\i18nInterface::class, \sys\services\i18nService::class);

