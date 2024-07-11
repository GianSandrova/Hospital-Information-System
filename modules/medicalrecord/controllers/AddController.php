<?php

namespace app\modules\medicalrecord\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use app\models\Rekam_medis;
use app\models\Personal;
use app\models\Faskes;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class AddController extends Controller
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
        
        $personalId = $request->post('personalId');
        $faskesId = $request->post('faskesId');
        $noRm = $request->post('no_rm');

        // Validasi input
        if (!$personalId || !$faskesId || !$noRm) {
            throw new BadRequestHttpException('Personal ID, Faskes ID, dan Nomor RM harus diisi.');
        }

        // Cek apakah Personal dan Faskes ada
        $personal = Personal::findOne($personalId);
        $faskes = Faskes::findOne($faskesId);

        if (!$personal || !$faskes) {
            throw new NotFoundHttpException('Personal atau Faskes tidak ditemukan.');
        }

        // Cek apakah rekam medis sudah ada
        $existingRekamMedis = Rekam_medis::findOne(['personal_id' => $personalId, 'faskes_id' => $faskesId]);
        if ($existingRekamMedis) {
            return [
                'code' => 409,
                'message' => 'Rekam Medis sudah ada untuk Personal dan Faskes ini.',
            ];
        }

        // Buat rekam medis baru
        $rekamMedis = new Rekam_medis();
        $rekamMedis->personal_id = $personalId;
        $rekamMedis->faskes_id = $faskesId;
        $rekamMedis->no_rm = $noRm;

        if ($rekamMedis->save()) {
            return [
                'code' => 201,
                'message' => 'Rekam Medis berhasil ditambahkan',
                'data' => [
                    'id' => $rekamMedis->id,
                    'personalId' => $rekamMedis->personal_id,
                    'faskesId' => $rekamMedis->faskes_id,
                    'no_rm' => $rekamMedis->no_rm,
                    'nama_lengkap' => $personal->nama_lengkap,
                    'tanggal_lahir' => $personal->tanggal_lahir,
                    'jenis_kelamin' => $personal->jenis_kelamin,
                    'nama_faskes' => $faskes->nama_faskes,
                ],
            ];
        } else {
            return [
                'code' => 400,
                'message' => 'Gagal menambahkan Rekam Medis',
                'errors' => $rekamMedis->errors,
            ];
        }
    }

    private function validateToken($token)
    {
        // Implementasi validasi token JWT
        // Return true jika valid, false jika expired atau invalid
        return !empty($token);
    }
}