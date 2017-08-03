<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

namespace sys\components\controllers;

use Yii;
use sys\components\rbac\HybridManager;

/**
 * Front default controller.
 */
class FrontController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $config = Yii::$app->get('config')->component('sys');
        $access = Yii::$app->user->can(HybridManager::P_BACKEND_ACCESS);
        if ($config->get('denyAccess', false)) {
            if (!$access && !in_array($action->id, $this->allowActions())) {
                exit($this->renderPartial('@theme/deny-access'));
            }
        }
        if ($access && $config->get('assets.isPermissionForcedCopy', false)) {
            Yii::$app->assetManager->forceCopy = true;
        }
        return parent::beforeAction($action);
    }

    /**
     * List of allowed action with deny access.
     * @return array
     */
    public function allowActions() : array
    {
        return ['login'];
    }
}