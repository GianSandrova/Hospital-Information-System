<?php

namespace app\controllers;

use yii\web\Controller;
use yii\httpclient\Client;
use yii\web\Response;

class GetPoliController extends Controller{
    public function actionIndex()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('https://demo-rs.emesys.id/app/web/index.php')
            ->setData([
                'r'=>'mobile/service-list/get-poli-rs'
            ])
            ->addHeaders([
                'no_handphone' => '085271988421',
                'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJub19oYW5kcGhvbmUiOiIwODUyNzE5ODg0MjEiLCJkYXRlIjoxNzIxMTQ5MjAwMDAwfQ.cxnjNVqL2KZ2HRefVV0KOv_UojosAsZFdJKZ9wDCXCg',
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