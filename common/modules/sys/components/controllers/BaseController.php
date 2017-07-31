<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

namespace sys\components\controllers;

use Yii;
use yii\web\Response;

/**
 * Default web controller.
 */
class BaseController  extends \yii\web\Controller
{
    /**
     * Is backend
     * @var bool
     */
    public $isBackend = false;

    /**
     * @var array
     */
    public $output = ['status' => 0];

    /**
     * @var bool
     */
    protected $autoAjax = true;

    public function init()
    {
        Yii::setAlias('@theme', $this->view->theme->getBasePath());
        Yii::$app->assetManager->forceCopy = Yii::$app->config->component('sys.assets.forcedCopy', false);
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function afterAction($action, $result)
    {
        if (Yii::$app->request->isAjax && $this->autoAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $this->output;
        }
        return parent::afterAction($action, $result);
    }
}