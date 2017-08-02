<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

namespace themes\backend\imperial\assets;

use Yii;
use sys\components\web\AssetBundle;

class ThemeAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@themes/backend/imperial/assets/dist';

    /**
     * @inheritdoc
     */
    public $css = [
        '//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic',
        '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
        '//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css',
        'css/adminLTE.css',
        'css/skins/_all-skins.min.css',
        'css/style.css'
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        '//cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js',
        'js/adminLTE.js',
        'js/app.js',
        '//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js',
        '//oss.maxcdn.com/respond/1.4.2/respond.min.js'
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'sys\assets\SysAsset'
    ];
}