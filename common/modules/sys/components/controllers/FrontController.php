<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

namespace sys\components\controllers;

/**
 * Front default controller.
 */
class FrontController extends BaseController
{
    /**
     * List of allowed action with deny access.
     * @var array
     */
    public $allow = ['login'];
}