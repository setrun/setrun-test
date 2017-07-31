<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

namespace sys\components\controllers;

use Yii;

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
        $config = Yii::$app->config;
        if ($config->component('sys.denyAccess', false)) {
            if (!Yii::$app->user->can('notDenyAccess') && !in_array($action->id, $this->allowActions())) {
                exit($this->renderPartial('@theme/deny-access'));
            }
        }
        if ($config->component('sys.assets.isPermissionForcedCopy', false)) {
            if (Yii::$app->user->can('assetsForcedCopy')) {
                Yii::$app->assetManager->forceCopy = true;
            }
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