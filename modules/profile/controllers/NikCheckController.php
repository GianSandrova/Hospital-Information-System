<?php

namespace app\modules\profile\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use app\models\Personal;

class NikCheckController extends Controller
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
        $nik = $request->post('nik');

        if (!$nik) {
            return [
                'code' => 400,
                'message' => 'NIK harus diisi',
            ];
        }

        $personal = Personal::findOne(['nik' => $nik]);

        if ($personal) {
            return [
                'code' => 409,
                'message' => 'NIK Sudah Digunakan',
            ];
        }

        return [
            'code' => 200,
            'message' => 'NIK Tersedia',
        ];
    }
}