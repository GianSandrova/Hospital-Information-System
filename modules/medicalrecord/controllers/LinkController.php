<?php
namespace app\modules\medicalrecord\controllers;

use Yii;
use yii\rest\Controller;
use app\models\Rekam_medis;
use app\models\Personal;
use yii\web\UnauthorizedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\filters\auth\HttpBearerAuth;

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

        $request = Yii::$app->request->getBodyParams();

        if (!isset($request['profilId'], $request['faskesId'], $request['no_rm'])) {
            return [
                'code' => 400,
                'message' => 'Bad Request: Missing required parameters',
            ];
        }

        $personal = Personal::findOne($request['profilId']);
        if (!$personal) {
            return [
                'code' => 404,
                'message' => 'Medical Record Tidak Ditemukan',
            ];
        }

        // Periksa apakah rekam medis sudah ada
        $existingRecord = Rekam_medis::findOne([
            'personal_id' => $request['profilId'],
            'faskes_id' => $request['faskesId'],
        ]);

        if ($existingRecord) {
            return [
                'code' => 403,
                'message' => 'Tidak Diperbolehkan Menautkan Rekam Medis: Rekam medis sudah terhubung',
            ];
        }

        // Buat rekam medis baru
        $rekamMedis = new Rekam_medis();
        $rekamMedis->personal_id = $request['profilId'];
        $rekamMedis->faskes_id = $request['faskesId'];
        $rekamMedis->no_rm = $request['no_rm'];

        if ($rekamMedis->save()) {
            return [
                'code' => 200,
                'message' => 'Medical Record Terhubung',
                'data' => [
                    [
                        'personalId' => $rekamMedis->personal_id,
                        'faskesId' => $rekamMedis->faskes_id,
                        'no_rm' => $rekamMedis->no_rm,
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
        } else {
            return [
                'code' => 500,
                'message' => 'Gagal menghubungkan Medical Record',
                'errors' => $rekamMedis->errors,
            ];
        }
    }
}