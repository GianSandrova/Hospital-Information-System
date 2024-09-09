<?php

namespace app\modules\faskes\controllers;

use Yii;
use app\helpers\login_helper;
use yii\rest\Controller;
use app\models\Faskes;
use yii\filters\auth\HttpBearerAuth;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;;
use yii\filters\Cors;

class FaskesController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => Yii::$app->params['corsOrigin'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 86400,
                'Access-Control-Expose-Headers' => [],
            ],
        ];
        return $behaviors;
    }


    // Create Action
    public function actionCreate()
    {
        $faskes = new Faskes();

        if ($faskes->load(Yii::$app->request->post()) && $faskes->save()) {
            Yii::$app->session->setFlash('success', 'Data Faskes berhasil ditambahkan.');
            return $this->redirect(['/site/faskes']);
        }

        return $this->redirect(['/site/faskes']);
    }

    // Update Action
    public function actionUpdate($id)
    {
        if (empty($id)) {
            Yii::$app->session->setFlash('error', 'ID tidak boleh kosong.');
            return $this->redirect(['/site/faskes']); 
        }
    
        $faskes = $this->findModel($id);
    
        if ($faskes->load(Yii::$app->request->post())) {
            if ($faskes->save()) {
                Yii::$app->session->setFlash('success', 'Data Faskes berhasil diperbarui.');
                return $this->redirect(['/site/faskes']);
            } else {
                Yii::$app->session->setFlash('error', 'Gagal memperbarui data. Silakan cek kembali inputan Anda.');
                // Tetap merender halaman faskes meskipun update gagal
                return $this->redirect(['/site/faskes']);
            }
        }
    

        return $this->redirect(['/site/faskes']);
    }    
    
    // Delete Action
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        Yii::$app->session->setFlash('success', 'Data Faskes berhasil dihapus.');
        return $this->redirect(['faskes']);
    }

    // Find Model
    protected function findModel($id)
    {
        if (($model = Faskes::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
