<?php

namespace app\controllers;

// use yii\web\Controller;
use yii\rest\Controller;
use yii\httpclient\Client;
use yii\web\Response;
use app\models\endpoint;

class GetPoliController extends Controller{
    public function behavior(){
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
            'except' => ['options'],
        ];
        return $behaviors;
    }
    public function actionIndex()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $tokenmobile = Yii::$app->request->post('tokenmobile');
        $no_telepon = Yii::$app->request->post('no_telepon');
        $faskes_id= Yii::$app->request->post('faskes_id');
        if ($faskes_id === null) {
            return ['error' => true, 'message' => 'faskes_id is required'];
        }

        $endpoint = Endpoint::findOne(['faskes_id' => $faskes_id, 'name' => 'getPoli']);
        if (!$endpoint) {
            return ['error' => true, 'message' => 'Invalid faskes_id or endpoint not found'];
        }

        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($endpoint -> url)
            ->setData([
                'r'=>'mobile/service-list/get-poli-rs'
            ])
            ->addHeaders([
                'no_handphone' => $no_telepon,
                'token' => $tokenmobile,
                'Accept' => 'application/json',
            ])
            ->send();

        if ($response->isOk) {
            return $response->data;
        } else {
            return [
                'error' => true,
                'message' => 'Gagal mengambil data poli',
                'details' => $response->content
            ];
        }
    }
}