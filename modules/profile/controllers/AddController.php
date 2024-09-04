<?php

namespace app\modules\profile\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use app\models\Personal;
use yii\web\BadRequestHttpException;
use yii\filters\Cors;
use app\helpers\login_helper;

class AddController extends Controller
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
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $model = new Personal();

        $model->attributes = $request->post();
        $model->user_id = Yii::$app->user->id; // Mengambil ID user yang sedang login

        if ($model->validate() && $model->save()) {
            return [
                'code' => 201,
                'message' => 'ProfileCreated',
                'data' => [
                    [
                        'id' => $model->id,
                        'user_id' => $model->user_id,
                        'no_rm'=>$model->id_pasienuser,
                        'relasi' => $model->relasi,
                        'nik' => $model->nik,
                        'nama_lengkap' => $model->nama_lengkap,
                        'jenis_kelamin' => $model->jenis_kelamin,
                        'tanggal_lahir' => $model->tanggal_lahir,
                        'no_hp' => $model->no_hp,
                        'no_wa' => $model->no_wa,
                        'email' => $model->email,
                    ]
                ],
            ];
        } else {
            return [
                'code' => 412,
                'message' => 'Profile Create Failed',
                'errors' => $model->errors,
            ];
        }
    }
}