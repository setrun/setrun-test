<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

namespace sys\assets;

use yii\web\AssetBundle;

/**
 * Login asset bundle.
 */
class LoginAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@sys/assets/dist';

    /**
     * @inheritdoc
     */
    public $css = [
        '//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css',
        'css/login.css'
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        'js/login.js',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}