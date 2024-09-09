<?php

namespace app\controllers;

use app\models\Faskes;
use app\models\User;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\bootstrap5\ActiveForm;
use yii\web\NotFoundHttpException;

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
        return $this->render('akun',['akun'=>User::find()->all()]);
    }

    public function actionFaskes()
    {
        $faskesList = Faskes::find()->all(); // Untuk daftar faskes
        $newFaskes = new Faskes(); // Untuk form tambah baru
        
        return $this->render('faskes', [
            'faskesList' => $faskesList,
            'newFaskes' => $newFaskes
        ]);
    }

    public function actionUpdateFaskes($id)
    {
        $faskes = Faskes::findOne($id);
        
        if (!$faskes) {
            throw new NotFoundHttpException('The requested Faskes does not exist.');
        }
        
        if ($faskes->load(Yii::$app->request->post()) && $faskes->save()) {
            Yii::$app->session->setFlash('success', 'Faskes successfully updated.');
            return $this->redirect(['faskes']);
        }
        
        return $this->render('faskes', [
            'faskesList' => Faskes::find()->all(),
            'faskes' => $faskes
        ]);
    }

    public function actionGetFaskes($id){
        $faskes = Faskes::findOne($id);
        return $this->render('view',['faskes' => $faskes]);
    }
    
}
