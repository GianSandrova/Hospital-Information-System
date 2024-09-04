<?php

namespace app\controllers;

use app\models\Faskes;
use Yii;
use yii\web\Controller;
use yii\web\Response;

class SiteController extends Controller
{
    public function actionLogin()
    {
        $this->layout = false;
        return $this->render('login');
    }
    
    public function actionDashboard()
    {
        return $this->render('dashboard');
    }
    public function actionAkun(){
        return $this->render('akun');
    }
}
