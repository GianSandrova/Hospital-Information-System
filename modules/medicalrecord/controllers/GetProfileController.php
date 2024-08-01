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

class GetProfileController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        return $behaviors;
    }

    public function actionIndex($profileId)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $personal = Personal::findOne($profileId);
        if (!$personal) {
            return [
                'code' => 404,
                'message' => 'Medical Record Tidak Ditemukan',
            ];
        }

        $rekamMedis = Rekam_medis::find()
            ->where(['personal_id' => $profileId])
            ->with('faskes')
            ->all();

        if (empty($rekamMedis)) {
            return [
                'code' => 404,
                'message' => 'Medical Record Tidak Ditemukan',
            ];
        }

        $data = [];
        foreach ($rekamMedis as $rm) {
            $data[] = [
                'personalId' => $rm->personal_id,
                'faskesId' => $rm->faskes_id,
                'no_rm' => $rm->no_rm,
                'user_id' => $personal->user_id,
                'relasi' => $personal->relasi,
                'nik' => $personal->nik,
                'nama_lengkap' => $personal->nama_lengkap,
                'jenis_kelamin' => $personal->jenis_kelamin,
                'tanggal_lahir' => $personal->tanggal_lahir,
                'no_hp' => $personal->no_hp,
                'no_wa' => $personal->no_wa,
                'email' => $personal->email,
            ];
        }

        return [
            'code' => 200,
            'message' => 'Medical Record',
            'data' => $data,
        ];
    }

}
