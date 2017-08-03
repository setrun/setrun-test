<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

namespace sys\components\controllers;


use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use sys\components\rbac\HybridManager;

/**
 * Class BackController.
 */
class BackController extends BaseController
{
    /**
     * @inheritdoc
     */
    public $isBackend = true;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [HybridManager::P_BACKEND_ACCESS]
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST']
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $config = Yii::$app->get('config')->component('sys');
        $this->view->theme->setBasePath(Yii::getAlias('@themes/backend/' . $config->get('backend.theme')));
        Yii::$app->assetManager->forceCopy = $config->get('backend.assets.forcedCopy', false);
        parent::init();
    }
}