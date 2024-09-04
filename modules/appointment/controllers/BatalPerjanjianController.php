<?php

namespace app\modules\appointment\controllers;

use app\helpers\login_helper;
use Yii;
use yii\rest\Controller;
use yii\httpclient\Client;
use yii\web\Response;
use app\models\endpoint;
use yii\filters\Cors;

class BatalPerjanjianController extends Controller
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
    
    public function actionIndex()
    {
        $requestData = Yii::$app->request->post();
        $user = login_helper::findUser($requestData['username']);
        if (!empty($user)) {
            $tokenCore = login_helper::getTokenMobile($user);
            if ($requestData['token_core'] == $tokenCore) {
                Yii::$app->response->format = Response::FORMAT_JSON;
         
                $id_perjanjian = $requestData['id_perjanjian'];
                $token = $requestData['token'];
                $faskes_id = $requestData['id_faskes'] ?? null;
        
                if ($faskes_id === null) {
                    return ['error' => true, 'message' => 'faskes_id is required'];
                }
        
                $endpoint = Endpoint::findOne(['faskes_id' => $faskes_id]);
                if (!$endpoint) {
                    return ['error' => true, 'message' => 'Invalid faskes_id or endpoint not found'];
                }
        
                $url = $endpoint->url . '?r=mobile/service-post-umum/batal-perjanjian';
        
                $client = new Client();
                $response = $client->createRequest()
                    ->setMethod('POST')
                    ->setUrl($url)
                    ->setFormat(Client::FORMAT_JSON)
                    ->setData([
                        'username' => $endpoint->faskes->user_api,
                        'id_perjanjian' => $id_perjanjian,
                        'token' => $endpoint->faskes->token_mobile,
                    ])
                    ->send();
        
                if ($response->isOk) {
                    $responseData = $response->data;
                    return [
                        'success' => true,
                        'data' => $responseData
                    ];
                } else {
                    return [
                        'error' => true,
                        'message' => 'Gagal membatalkan perjanjian',
                        'status' => $response->statusCode,
                        'details' => $response->content
                    ];
                }
            } else {
                $response = [
                    'metadata' => [
                        'message' => 'Token core tidak valid!',
                        'code' => 401
                    ]
                ];
            }
        } else {
            $response = [
                'metadata' => [
                    'message' => 'No Handphone tidak ditemukan pada database!',
                    'code' => 201
                ]
            ];
        }
        return $response;
    }
}