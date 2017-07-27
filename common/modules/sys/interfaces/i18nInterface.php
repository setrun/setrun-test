<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

namespace sys\interfaces;

interface i18nInterface
{
    public function t($category, $message, $params = [], $language = null) : string;
}