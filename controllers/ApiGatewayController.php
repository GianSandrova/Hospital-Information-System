<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\httpclient\Client;
use app\models\Endpoint;
use yii\httpclient\Exception as HttpClientException;

class ApiGatewayController extends Controller
{
    // Fungsi untuk mendapatkan URL Clinic berdasarkan faskes_id
    private function getClinicUrl($faskes_id)
    {
        $endpoint = Endpoint::findOne(['faskes_id' => $faskes_id]);
        return $endpoint ? $endpoint->url : null;
    }

    // Action untuk mendapatkan data dokter
    public function actionGetDokter()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $faskes_id = $request->post('faskes_id');
        $token = $request->post('token');

        // Ambil URL endpoint Emesys Clinic berdasarkan faskes_id
        $url = $this->getClinicUrl($faskes_id);

        if (!$url) {
            return [
                'code' => 404,
                'message' => 'Endpoint not found'
            ];
        }

        $apiUrl = $url . 'app/web/index.php?r=mobile/service-list/get-dokter-rs';

        // Buat permintaan ke API Emesys Clinic
        try {
            $client = new Client();
            $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl($apiUrl)
                ->addHeaders(['Authorization' => 'Bearer ' . $token])
                ->send();

            if ($response->isOk) {
                return $response->data;
            }

            return [
                'code' => $response->statusCode,
                'message' => 'Failed to fetch data'
            ];
        } catch (HttpClientException $e) {
            return [
                'code' => 500,
                'message' => 'HTTP Client Exception: ' . $e->getMessage()
            ];
        } catch (\Exception $e) {
            return [
                'code' => 500,
                'message' => 'General Exception: ' . $e->getMessage()
            ];
        }
    }

    // Action untuk mendapatkan data poli
    public function actionGetPoli()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $faskes_id = $request->post('faskes_id');
        $token = $request->post('token');

        if (!$faskes_id || !$token) {
            return [
                'code' => 400,
                'message' => 'Missing required parameters'
            ];
        }

        // Ambil URL endpoint Emesys Clinic berdasarkan faskes_id
        $url = $this->getClinicUrl($faskes_id);

        if (!$url) {
            return [
                'code' => 404,
                'message' => 'Endpoint not found'
            ];
        }

        $apiUrl = $url . 'app/web/index.php?r=mobile/service-list/get-poli-rs';

        // Buat permintaan ke API Emesys Clinic
        try {
            $client = new Client();
            $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl($apiUrl)
                ->addHeaders(['Authorization' => 'Bearer ' . $token])
                ->send();

            if ($response->isOk) {
                return $response->data;
            }

            return [
                'code' => $response->statusCode,
                'message' => 'Failed to fetch data'
            ];
        } catch (HttpClientException $e) {
            return [
                'code' => 500,
                'message' => 'HTTP Client Exception: ' . $e->getMessage()
            ];
        } catch (\Exception $e) {
            return [
                'code' => 500,
                'message' => 'General Exception: ' . $e->getMessage()
            ];
        }
    }

    // Action untuk mendapatkan data jadwal
    public function actionGetJadwal()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $faskes_id = $request->post('faskes_id');
        $token = $request->post('token');

        if (!$faskes_id || !$token) {
            return [
                'code' => 400,
                'message' => 'Missing required parameters'
            ];
        }

        // Ambil URL endpoint Emesys Clinic berdasarkan faskes_id
        $url = $this->getClinicUrl($faskes_id);

        if (!$url) {
            return [
                'code' => 404,
                'message' => 'Endpoint not found'
            ];
        }

        $apiUrl = $url . 'app/web/index.php?r=mobile/service-list/get-jadwal-rs';

        // Buat permintaan ke API Emesys Clinic
        try {
            $client = new Client();
            $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl($apiUrl)
                ->addHeaders(['Authorization' => 'Bearer ' . $token])
                ->send();

            if ($response->isOk) {
                return $response->data;
            }

            return [
                'code' => $response->statusCode,
                'message' => 'Failed to fetch data'
            ];
        } catch (HttpClientException $e) {
            return [
                'code' => 500,
                'message' => 'HTTP Client Exception: ' . $e->getMessage()
            ];
        } catch (\Exception $e) {
            return [
                'code' => 500,
                'message' => 'General Exception: ' . $e->getMessage()
            ];
        }
    }
}
