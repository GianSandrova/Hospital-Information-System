<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\httpclient\Client;
use yii\web\Response;
use app\models\Endpoint;

class GetDokterController extends Controller
{
    public function actionIndex($faskes_id, $no_telepon , $tokenmobile )
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($faskes_id === null) {
            return ['error' => true, 'message' => 'faskes_id is required'];
        }
        
        $endpoint = Endpoint::findOne(['faskes_id' => $faskes_id, 'name' => 'getDokter']);
        if (!$endpoint) {
            return ['error' => true, 'message' => 'Invalid faskes_id or endpoint not found'];
        }


        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($endpoint -> url)
            ->setData([
                'r' => 'mobile/service-list/get-dokter-rs'
            ])
            ->addHeaders([
                'no_handphone' => $no_telepon,
                'token' => $tokenmobile,
                'Accept' => 'application/json',
            ])
            ->send();

        if ($response->isOk) {
            $dokterData = $response->data;
            
            // // Tambahkan faskes_id ke setiap dokter
            // foreach ($dokterData['data'] as &$dokter) {
            //     $dokter['faskes_id'] = $faskes_id;
            // }
            
            return $dokterData;
        } else {
            return [
                'error' => true,
                'message' => 'Gagal mengambil data dokter',
                'details' => $response->content
            ];
        }
    }
}