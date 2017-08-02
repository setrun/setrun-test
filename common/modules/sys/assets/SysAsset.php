<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

namespace sys\assets;

use sys\components\web\AssetBundle;

/**
 * Class SysAsset.
 */
class SysAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@sys/assets/dist';

    /**
     * @inheritdoc
     */
    public $js = [
        'js/sys.js'
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}