<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

namespace sys\components\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

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
                        'roles' => ['backend']
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
        $this->view->theme->setBasePath(
            '@themes/backend/' . Yii::$app->get('config')->component('sys.backend.theme')
        );
        Yii::$app->assetManager->forceCopy =
                                 Yii::$app->get('config')->component('sys.backend.assets.forcedCopy', false);
        parent::init();

    }
}