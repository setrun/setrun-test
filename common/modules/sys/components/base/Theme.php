<?php

namespace sys\components\base;

use Yii;

/**
 * Theme represents an application theme.
 */
class Theme extends \yii\base\Theme
{
    /**
     * @inheritdoc
     */
    public function applyTo($path)
    {
        $base = Yii::$app->getBasePath();
        $file = str_replace(['views/', $base], ['', $this->getBasePath() . '/views'], $path);
        return !is_file($file) ? $path : $file;
    }
}