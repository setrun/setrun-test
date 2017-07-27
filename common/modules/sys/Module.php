<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

namespace sys;

use Yii;

class Module extends \yii\base\Module
{
    public const VERSION = '1.0';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'sys\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (Yii::$app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'sys\commands';
        }
    }
}