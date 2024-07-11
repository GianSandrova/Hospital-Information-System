<?php

namespace app\modules\faskes\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use app\models\Faskes;
use app\models\Personal;
use app\models\Rekam_medis;
use yii\web\UnauthorizedHttpException;
use yii\web\NotFoundHttpException;

class ByUserController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $request = Yii::$app->request;
        

        $userId = $request->post('userId');

        if (!$userId) {
            return [
                'code' => 400,
                'message' => 'User ID harus diisi',
            ];
        }

        // Cari faskes berdasarkan user ID
        $faskes = $this->findFaskesByUserId($userId);

        if (empty($faskes)) {
            return [
                'code' => 404,
                'message' => 'Faskes Tidak Ditemukan',
            ];
        }

        // Format response
        $response = [
            'code' => 200,
            'message' => 'Faskes',
            'data' => [],
        ];

        foreach ($faskes as $f) {
            $response['data'][] = [
                'id' => $f->id,
                'nama_faskes' => $f->nama_faskes,
                'alamat' => $f->alamat,
                'deskripsi' => $f->deskripsi,
                'logo' => $f->logo,
                'longitud' => $f->longtitud,
                'latitud' => $f->latitude,
            ];
        }

        return $response;
    }


    private function findFaskesByUserId($userId)
    {
        // Mencari personal_id berdasarkan user_id
        $personal = Personal::findOne(['user_id' => $userId]);
        
        if (!$personal) {
            return [];
        }

        // Mencari faskes_id berdasarkan personal_id melalui rekam_medis
        $rekamMedis = Rekam_medis::find()
            ->select('faskes_id')
            ->where(['personal_id' => $personal->id])
            ->groupBy('faskes_id')
            ->all();

        $faskesIds = array_map(function($rm) {
            return $rm->faskes_id;
        }, $rekamMedis);

        // Mencari faskes berdasarkan faskes_id yang ditemukan
        return Faskes::find()
            ->where(['id' => $faskesIds])
            ->all();
    }
}