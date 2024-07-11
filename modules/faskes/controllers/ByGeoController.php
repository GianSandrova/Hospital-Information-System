<?php

namespace app\modules\faskes\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use app\models\Faskes;
use yii\web\UnauthorizedHttpException;
use yii\web\NotFoundHttpException;

class ByGeoController extends Controller
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
        

        $lon = $request->post('lon');
        $lat = $request->post('lat');

        // Validasi input
        if (!$lon || !$lat) {
            return [
                'code' => 400,
                'message' => 'Longitude dan Latitude harus diisi',
            ];
        }

        // Konversi format longitude dan latitude
        $lon = str_replace(',', '.', $lon);
        $lat = str_replace(',', '.', $lat);

        // Cari faskes berdasarkan geolokasi
        $faskes = $this->findFaskesByGeo($lon, $lat);

        if (empty($faskes)) {
            return [
                'code' => 404,
                'message' => 'Faskes Tidak Ditemukan',
            ];
        }

        // Format response
        $response = [
            'code' => 200,
            'message' => 'Faskes',
            'data' => [],
        ];

        foreach ($faskes as $f) {
            $response['data'][] = [
                'id' => $f->id,
                'nama_faskes' => $f->nama_faskes,
                'alamat' => $f->alamat,
                'deskripsi' => $f->deskripsi,
                'logo' => $f->logo, // Assume this is already in base64
                'longitud' => $f->longtitud,
                'latitud' => $f->latitude,
            ];
        }

        return $response;
    }

    private function validateToken($token)
    {
        // Implementasi validasi token JWT
        // Return true jika valid, false jika expired atau invalid
        // Anda perlu mengimplementasikan logika validasi token di sini
        // Contoh sederhana:
        return !empty($token);
    }

    private function findFaskesByGeo($lon, $lat)
    {
        return Faskes::find()
            ->where(['longtitud' => $lon, 'latitude' => $lat])
            ->all();
    }
}