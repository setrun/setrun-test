<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

define('ROOT_PATH', __DIR__  . '/../../..');
define('APP_PATH', __DIR__  . '/..');

require(ROOT_PATH  . '/vendor/autoload.php');
require(ROOT_PATH  . '/functions.php');

require(APP_PATH  . '/environment.php');
require(ROOT_PATH . '/vendor/yiisoft/yii2/Yii.php');
require(ROOT_PATH . '/common/config/bootstrap.php');
require(APP_PATH  . '/config/bootstrap.php');

\Yii::$container->setSingleton(\sys\interfaces\ConfiguratorInterface::class, \sys\components\Configurator::class);

$configurator = Yii::$container->get(\sys\interfaces\ConfiguratorInterface::class);
$configurator->setEnv(\sys\components\Configurator::WEB);
$configurator->setCachePath(APP_PATH . '/runtime/cache_configurator');
$configurator->load([
    ROOT_PATH . '/common/config/main.php',
    ROOT_PATH . '/common/config/main-local.php',
    APP_PATH  . '/config/main.php',
    APP_PATH  . '/config/main-local.php'
]);
(new yii\web\Application($configurator->application()))->run();





