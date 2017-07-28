<?php

namespace sys\components\web;

/**
 * AssetBundle represents a collection of asset files, such as CSS, JS, images.
 */
class AssetBundle extends \yii\web\AssetBundle
{
    /**
     * @inheritdoc
     */
    public static function register($view, array $files = []) : \yii\web\AssetBundle
    {
        $bundle =  parent::register($view);
        if (!empty($files)) {
            foreach ($files as $file) {
                if (strpos($file, '.css') !== false) {
                    $view->registerCssFile($bundle->baseUrl . '/' . $file, ['depends' => static::className()]);
                } elseif (strpos($file, '.js') !== false) {
                    $view->registerJsFile($bundle->baseUrl . '/' . $file,  ['depends' => static::className()]);
                }
            }
        }
        return $bundle;
    }
}