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
require(APP_PATH . '/config/bootstrap.php');
require(__DIR__ . '/../config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../config/main.php'),
    require(__DIR__ . '/../config/main-local.php'),
    require(APP_PATH . '/config/main.php'),
    require(APP_PATH . '/config/main-local.php')
);

(new yii\web\Application($config))->run();