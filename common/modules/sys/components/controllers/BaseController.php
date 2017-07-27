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
     * @var array
     */
    public $output = ['status' => 0];

    /**
     * @var bool
     */
    protected $autoAjax = true;

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