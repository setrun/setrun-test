<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

namespace sys\controllers;

use Yii;
use sys\components\controllers\BackController;

/**
 * Class BackendController.
 */
class BackendController extends BackController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}