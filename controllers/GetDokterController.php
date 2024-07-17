<?php

namespace app\controllers;

use yii\web\Controller;
use yii\httpclient\Client;
use yii\web\Response;

class GetDokterController extends Controller
{
    public function actionIndex()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('https://demo-rs.emesys.id/app/web/index.php')
            ->setData([
                'r' => 'mobile/service-list/get-dokter-rs'
            ])
            ->addHeaders([
                'no_handphone' => '085271988421',
                'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJub19oYW5kcGhvbmUiOiIwODUyNzE5ODg0MjEiLCJkYXRlIjoxNzIxMDYyODAwMDAwfQ.pRGQJBud5AOX5_AWKHfSRcNA4Npo-3FZM5IZMTnf-AI',
                'Accept' => 'application/json',
            ])
            ->send();

        if ($response->isOk) {
            return $response->data;
        } else {
            return [
                'error' => true,
                'message' => 'Gagal mengambil data dokter',
                'details' => $response->content
            ];
        }
    }
}