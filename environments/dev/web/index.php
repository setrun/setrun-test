<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

define('ROOT_DIR', __DIR__  . '/..');

require(ROOT_DIR  . '/vendor/autoload.php');
require(ROOT_DIR  . '/functions.php');

findApplicationByDomain();

require(APP_DIR  . '/environment.php');
require(ROOT_DIR . '/vendor/yiisoft/yii2/Yii.php');
require(ROOT_DIR . '/common/bootstrap.php');
require(APP_DIR  . '/bootstrap.php');

$configurator = Yii::$container->get(\sys\components\Configurator::class);
$configurator->setEnv(\sys\components\Configurator::WEB);
$configurator->setCachePath(APP_DIR . '/runtime/cache_configurator');
$configurator->load([
    ROOT_DIR . '/common/config/main.php',
    ROOT_DIR . '/common/config/web.php',
    APP_DIR  . '/config/main.php',
    APP_DIR  . '/config/main-local.php',
    APP_DIR  . '/config/web.php',
    APP_DIR  . '/config/web-local.php'
]);
(new yii\web\Application($configurator->application()))->run();

