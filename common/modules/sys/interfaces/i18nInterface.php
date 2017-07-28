<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

namespace sys\interfaces;

interface i18nInterface
{
    /**
     * Translates a message to the specified language.
     * @param string $category
     * @param string $message
     * @param array  $params
     * @param null   $language
     * @return string
     */
    public function t($category, $message, $params = [], $language = null) : string;
}