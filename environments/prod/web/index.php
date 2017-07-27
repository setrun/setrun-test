<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../functions.php');

findApplicationByDomain();

require(APP_PATH . '/environment.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../common/config/bootstrap.php');
require(APP_PATH . '/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../common/config/main.php'),
    require(__DIR__ . '/../common/config/main-local.php'),
    require(APP_PATH . '/config/main.php'),
    require(APP_PATH . '/config/main-local.php'),
    require(__DIR__ . '/../common/modules/sys/config/sys.php')
);

(new yii\web\Application($config))->run();

