<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

namespace sys\components;

use Yii;
use sys\services\i18nService;
use yii\base\BootstrapInterface;
use sys\interfaces\i18nInterface;

/**
 * Class Bootstrap.
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app) : void
    {
        $container = Yii::$container;
        $container->setSingleton(i18nInterface::class, i18nService::class);
    }
}