<?php

namespace app\modules\jadwal\controllers;

use app\helpers\login_helper;
use yii;
use yii\rest\Controller;
use yii\httpclient\Client;
use yii\web\Response;
use app\models\Endpoint;
use yii\filters\Cors;
use yii\filters\auth\HttpBearerAuth;

class GetJadwalController extends Controller
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
        return $behaviors;
    }
    
    public function actionJadwal()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
    
        $id_departemen = Yii::$app->request->get('id_departemen');
        $faskes_id = Yii::$app->request->get('id_faskes');
    
        if ($faskes_id === null) {
            return ['error' => true, 'message' => 'faskes_id is required'];
        }
    
        $endpoint = Endpoint::findOne(['faskes_id' => $faskes_id]);
        if (!$endpoint) {
            return ['error' => true, 'message' => 'Invalid faskes_id or endpoint not found'];
        }
    
        $url = $endpoint->url . '?r=mobile/service-list-umum/get-jadwal-rs';
    
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($url)
            ->addHeaders([
                'id_departemen' => $id_departemen,
                'Accept' => 'application/json',
            ])
            ->send();
    
        if ($response->isOk) {
            $responseData = json_decode($response->content, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return [
                    'success' => true,
                    'data' => $responseData
                ];
            } else {
                return [
                    'error' => true,
                    'message' => 'Gagal memparse respons JSON',
                    'details' => substr($response->content, 0, 500)
                ];
            }
        } else {
            return [
                'error' => true,
                'message' => 'Gagal mengambil data jadwal',
                'status' => $response->statusCode,
                'details' => substr($response->content, 0, 500)
            ];
        }
    }


    public function actionIndex()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
    
        $tokenmobile = Yii::$app->request->post('tokenmobile');
        $no_telepon = Yii::$app->request->post('no_telepon');
        $faskes_id = Yii::$app->request->post('faskes_id');
        $id_departemen = Yii::$app->request->post('id_departemen');
    
        if ($faskes_id === null) {
            return ['error' => true, 'message' => 'faskes_id is required'];
        }
    
        $endpoint = Endpoint::findOne(['faskes_id' => $faskes_id, 'name' => 'getJadwal']);
        if (!$endpoint) {
            return ['error' => true, 'message' => 'Invalid faskes_id or endpoint not found'];
        }
    
        $url = $endpoint->url . '?r=mobile/service-list-umum/get-jadwal-rs';
    
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')  // Ubah metode menjadi POST
            ->setUrl($url)
            ->addHeaders([
                'no_handphone' => $no_telepon,
                'token' => $tokenmobile,
                'id_departemen'=>$id_departemen,
                'Accept' => 'application/json',
            ])
            ->send();
    
        if ($response->isOk) {
            $responseData = json_decode($response->content, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return [
                    'success' => true,
                    'data' => $responseData
                ];
            } else {
                return [
                    'error' => true,
                    'message' => 'Gagal memparse respons JSON',
                    'details' => substr($response->content, 0, 500)
                ];
            }
        } else {
            return [
                'error' => true,
                'message' => 'Gagal mengambil data jadwal',
                'status' => $response->statusCode,
                'details' => substr($response->content, 0, 500)
            ];
        }
    }
}