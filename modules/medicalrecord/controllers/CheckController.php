<?php

namespace app\modules\medicalrecord\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use app\models\Personal;
use app\models\Faskes;
use app\models\Rekam_medis;
use yii\web\UnauthorizedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

class CheckController extends Controller
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
        

        $profilId = $request->post('profilId');
        $faskesId = $request->post('faskesId');

        $rekamMedis = $this->findRekamMedis($profilId, $faskesId);

        if (!$rekamMedis) {
            return [
                'code' => 404,
                'message' => 'Medical Record Not Found',
            ];
        }

        $personal = Personal::findOne($rekamMedis->personal_id);

        return [
            'code' => 200,
            'message' => 'Medical Record Ditemukan',
            'data' => [
                [
                    'nik' => $personal->nik,
                    'no_rm' => $rekamMedis->no_rm,
                    'nama' => $personal->nama_lengkap,
                    'tglLahir' => $personal->tanggal_lahir,
                    'jenisKelamin' => $personal->jenis_kelamin,
                ]
            ],
        ];
    }

    private function findRekamMedis($profilId, $faskesId)
    {
        return Rekam_medis::findOne(['personal_id' => $profilId, 'faskes_id' => $faskesId]);
    }
}