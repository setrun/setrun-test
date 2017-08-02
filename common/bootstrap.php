<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

// Aliases
Yii::setAlias('@common', ROOT_DIR . '/common');
Yii::setAlias('@themes', ROOT_DIR . '/themes');
Yii::setAlias('@root',   ROOT_DIR . '');
Yii::setAlias('@sys',    ROOT_DIR . '/common/modules/sys');

// DI
\Yii::$container->setSingleton(\sys\components\Configurator::class);
\Yii::$container->setSingleton(\sys\interfaces\i18nInterface::class, \sys\services\i18nService::class);

