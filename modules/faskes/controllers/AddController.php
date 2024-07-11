<?php

namespace app\modules\faskes\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use app\models\Faskes;
use yii\web\BadRequestHttpException;

class AddController extends Controller
{

    public function actionIndex()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $model = new Faskes();

        // Set attributes from request
        $model->attributes = $request->post();

        // Generate user_api and password_api
        $model->user_api = $this->generateUserApi();
        $model->password_api = $this->generatePasswordApi();

        if ($model->validate() && $model->save()) {
            return [
                'code' => 201,
                'message' => 'Faskes Created',
                'data' => [
                    [
                        'id' => $model->id,
                        'client_id' => $model->client_id,
                        'nama_faskes' => $model->nama_faskes,
                        'alamat' => $model->alamat,
                        'deskripsi' => $model->deskripsi,
                        'logo' => $model->logo,
                        'is_aktif' => $model->is_aktif,
                        'is_bridging' => $model->is_bridging,
                        'ip_address' => $model->ip_address,
                        'user_api' => $model->user_api,
                        'password_api' => $model->password_api,
                        'longtitud' => $model->longtitud,
                        'latitude' => $model->latitude,
                    ]
                ],
            ];
        } else {
            return [
                'code' => 412,
                'message' => 'Faskes Create Failed',
                'errors' => $model->errors,
            ];
        }
    }

    private function generateUserApi()
    {
        return 'user_' . bin2hex(random_bytes(5));
    }

    private function generatePasswordApi()
    {
        return bin2hex(random_bytes(10));
    }
}

