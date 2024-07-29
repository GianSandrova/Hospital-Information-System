<?php

namespace app\modules\jadwal\controllers;

use yii\web\Controller;

/**
 * Default controller for the `v2` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
