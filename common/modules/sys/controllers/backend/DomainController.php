<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

namespace sys\controllers\backend;

use sys\components\controllers\BackController;

/**
 * Class DomainController.
 */
class DomainController extends  BackController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}