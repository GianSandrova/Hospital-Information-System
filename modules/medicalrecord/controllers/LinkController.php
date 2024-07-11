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

class LinkController extends Controller
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
        $noRm = $request->post('no_rm');
        $tglLahir = $request->post('tglLahir');

        $personal = Personal::findOne($profilId);
        if (!$personal || $personal->tanggal_lahir !== $tglLahir) {
            return [
                'code' => 404,
                'message' => 'Medical Record Tidak Ditemukan',
            ];
        }

        $existingRekamMedis = Rekam_medis::findOne(['personal_id' => $profilId, 'faskes_id' => $faskesId]);
        if ($existingRekamMedis) {
            return [
                'code' => 403,
                'message' => 'Tidak Diperbolehkan Menautkan Rekam Medis',
            ];
        }

        $rekamMedis = new Rekam_medis();
        $rekamMedis->personal_id = $profilId;
        $rekamMedis->faskes_id = $faskesId;
        $rekamMedis->no_rm = $noRm;

        if (!$rekamMedis->save()) {
            return [
                'code' => 400,
                'message' => 'Gagal menautkan rekam medis',
            ];
        }

        return [
            'code' => 200,
            'message' => 'Medical Record Terhubung',
            'data' => [
                [
                    'personalId' => $personal->id,
                    'faskesId' => $faskesId,
                    'no_rm' => $noRm,
                    'user_id' => $personal->user_id,
                    'relasi' => 'Diri Sendiri',
                    'nik' => $personal->nik,
                    'nama_lengkap' => $personal->nama_lengkap,
                    'jenis_kelamin' => $personal->jenis_kelamin,
                    'tanggal_lahir' => $personal->tanggal_lahir,
                    'no_hp' => $personal->no_hp,
                    'no_wa' => $personal->no_wa,
                    'email' => $personal->email,
                ]
            ],
        ];
    }

    private function findRekamMedis($profilId, $faskesId)
    {
        return Rekam_medis::findOne(['personal_id' => $profilId, 'faskes_id' => $faskesId]);
    }
}