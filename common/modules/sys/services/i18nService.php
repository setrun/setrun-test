<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

namespace sys\services;

use Yii;
use sys\interfaces\i18nInterface;

/**
 * Class i18nService.
 */
class i18nService implements i18nInterface
{
    /**
     * Translates a message to the specified language.
     * @param string $category
     * @param string $message
     * @param array  $params
     * @param null   $language
     * @return string
     */
    public function t($category, $message, $params = [], $language = null) : string
    {
       return Yii::t($category, $message, $params, $language);
    }
}