<?php

namespace app\controllers;

class BaseController extends controller{
    public function actionOptions($id = null) 
    {
        if (Yii::$app->getRequest()->getMethod() !== 'OPTIONS') {
            Yii::$app->getResponse()->setStatusCode(405);
            return;
        }
        
        Yii::$app->getResponse()->getHeaders()->set('Allow', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
        Yii::$app->getResponse()->setStatusCode(200);
    }
}
