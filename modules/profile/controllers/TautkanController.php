<?php

namespace app\modules\profile\controllers;

use Yii;
use yii\rest\Controller;
use app\helpers\login_helper;
use yii\filters\Cors;
use yii\httpclient\Client;
use yii\web\Response;
use app\models\Endpoint;
use app\models\Personal;
use app\models\Rekam_medis;
use app\models\User;
use yii\web\NotFoundHttpException;

class TautkanController extends Controller
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
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $token_core = Yii::$app->request->post('token_core');
        $find_user = User::find()->where(['auth_key' => $token_core])->one();

        // return ['id'=>$find_user->id];

        $username= Yii::$app->request->post('username');
        $faskes_id = Yii::$app->request->post('faskes_id');
        $nik = Yii::$app->request->post('nik');
        $type = Yii::$app->request->post('type');
        if ($type !== "NIK") {
            $cek_dulu_niknya = Personal::find()->where(['nik' => $nik])->one();
        } else {
            $cek_dulu_niknya = Personal::find()->where(['no_rm' => $nik])->one();
        }
        if (!$cek_dulu_niknya) {
            $endpoint = Endpoint::findOne(['faskes_id' => $faskes_id]);
            if (!$endpoint) {
                return ['success' => false, 'data' => ['code' => 400, 'message' => 'Invalid faskes_id or endpoint not found']];
            }

            $client = new Client();
            $response = $client->createRequest()
                ->setMethod('GET')
                ->setUrl($endpoint->url)
                ->setData([
                    'r' => 'mobile/service-list-umum/get-id-pasien'
                ])
                ->addHeaders([
                    'username' => $endpoint->faskes->user_api,
                    'nik' => $nik,
                    'type'=>$type,
                    'Accept' => 'application/json',
                ])
                ->send();
                if ($response->isOk) {
                    $responseData = json_decode($response->content, true);
                    $patientData = $responseData['data'] ?? [];
                    // return $responseData;

                $model = new Personal();

                // Memetakan data dari JSON ke atribut model
                $model->nama_lengkap = $patientData['nama_lengkap'] ?? null;
                $model->no_rm = strval($patientData['id_pasien'] ?? null);
                $model->jenis_kelamin = $patientData['jenis_kelamin'] ?? null;
                $model->tanggal_lahir = $patientData['tanggal_lahir'] ?? null;
                $model->tempat_lahir = $patientData['tempat_lahir'] ?? null;
                $model->user_id = $find_user ? $find_user->id : null;
                // $model->id_pasienuser = $patientData['id_pasien'] ?? null;

                // Mengatur atribut lain yang diperlukan
                $model->nik = $patientData['nik'] ?? null;
                $model->no_hp = "0";
                $model->is_delete = false;
                $model->relasi = 'Orang Lain';
                $model->status = 'sudah di acc';


                if ($model->save()) {

                    $rekam_medis= new Rekam_medis();
                    $rekam_medis->personal_id = $model->id;
                    $rekam_medis->faskes_id =$faskes_id;
                    $rekam_medis->no_rm = strval($patientData['id_pasien'] ?? null);
                    $rekam_medis->save();
                    return [
                        'success' => true,
                        'data' => [
                            'code' => 201,
                            'message' => 'Profile Created',
                            'pasien' => $model->attributes
                        ]
                    ];
                } else {
                    return [
                        'success' => false,
                        'data' => [
                            'code' => 500,
                            'message' => 'Failed to save personal data',
                            'errors' => $model->errors
                        ]
                    ];
                }
            } else {
                // Penanganan kesalahan jika respons tidak OK
                return [
                    'success' => false,
                    'data' => [
                        'code' => $response->statusCode,
                        'message' => 'Failed to retrieve patient data',
                        'details' => $response->content
                    ]
                ];
            }
        } else {
            return [
                'success' => true,
                'data' => [
                    'code' => 200,
                    'message' => 'Pasien Sudah Di Tambahkan Profilnya',
                ]
            ];
        }
    }

    public function actionCariPasien($nik, $type)
    {
        $header = Yii::$app->request->headers;
        $faskes_id = Yii::$app->request->post('faskes_id');
        $username = Yii::$app->request->post('username');
        $token_core = Yii::$app->request->post('token_core');
        // $no_telepon = $header->get('no_telepon');
        // $token_core = $header->get('token_core');
        // $type = $header->get('type');

        if (!$token_core || !$type) {
            return [
                'success' => false,
                'code' => 400,
                'message' => 'Missing required parameters',
            ];
        }

        $user = login_helper::findUser($username);
        if (empty($user)) {
            return [
                'success' => false,
                'code' => 404,
                'message' => 'User not found',
            ];
        }

        $token = login_helper::getTokenMobile($user);
        if ($token_core !== $token) {
            return [
                'success' => false,
                'code' => 401,
                'message' => 'Invalid token',
            ];
        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $endpoint = Endpoint::findOne(['faskes_id' => $faskes_id]);
        if (!$endpoint) {
            return ['success' => false, 'data' => ['code' => 400, 'message' => 'Invalid faskes_id or endpoint not found']];
        }

        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($endpoint->url)
            ->setData([
                'r' => 'mobile/service-list-umum/get-id-pasien'
            ])
            ->addHeaders([
                'nik' => $nik,
                'type' => $type,
                'Accept' => 'application/json',
            ])
            ->send();

        if ($response->isOk) {
            $responseData = json_decode($response->content, true);

            // Check if the response indicates that the search parameter is not registered
            if (isset($responseData['metadata']['code']) && $responseData['metadata']['code'] == 201) {
                return [
                    'success' => false,
                    'data' => [
                        'code' => 201,
                        'message' => $responseData['metadata']['message'] ?? ($type === 'NIK' ? 'NIK Belum Terdaftar di Faskes!' : 'No RM Tidak Ditemukan!'),
                    ]
                ];
            }

            $patientData = $responseData['data'] ?? [];
            $profileData = [
                'nik' => $patientData['nik'] ?? null,
                'nama_lengkap' => $patientData['nama_lengkap'] ?? null,
                'jenis_kelamin' => $patientData['jenis_kelamin'] ?? null,
                'tanggal_lahir' => $patientData['tanggal_lahir'] ?? null,
                'tempat_lahir' => $patientData['tempat_lahir'] ?? null,
                'no_rm' => $patientData['id_pasien'] ?? null,
            ];

            return [
                'success' => true,
                'data' => [
                    'code' => 200,
                    'message' => 'Success',
                    'data' => $profileData,
                ]
            ];
        } else {
            // Handle other error cases
            return [
                'success' => false,
                'data' => [
                    'code' => $response->statusCode,
                    'message' => 'Error fetching patient data',
                ]
            ];
        }
    }

    public function actionCheckStatusPasien()
    {
        $header = Yii::$app->request->headers;
        $faskes_id = Yii::$app->request->post('faskes_id');
        // $no_telepon = Yii::$app->request->post('no_telepon');
        $username = Yii::$app->request->post('username');
        $token_core = Yii::$app->request->post('token_core');
        $nik = Yii::$app->request->post('nik');
    
        if (!$nik || !$token_core) {
            return [
                'success' => false,
                'code' => 400,
                'message' => 'Missing required parameter: nik',
            ];
        }
        $user = login_helper::findUser($username);
        if (empty($user)) {
            return [
                'success' => false,
                'code' => 404,
                'message' => 'User not found',
            ];
        }

        $token = login_helper::getTokenMobile($user);
        if ($token_core !== $token) {
            return [
                'success' => false,
                'code' => 401,
                'message' => 'Invalid token',
            ];
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $endpoint = Endpoint::findOne(['faskes_id' => $faskes_id]);
        if (!$endpoint) {
            return ['success' => false, 'data' => ['code' => 400, 'message' => 'Invalid faskes_id or endpoint not found']];
        }
    
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($endpoint -> url)
            ->setData([
                'r' => 'mobile/service-list-umum/check-status-pasien'
            ])
            ->addHeaders([
                'nik' => $nik,
                'Accept' => 'application/json',
            ])
            ->send();
    
        if ($response->isOk) {
            $responseData = json_decode($response->content, true);
    
            if (isset($responseData['metadata']['code']) && $responseData['metadata']['code'] == 200) {
                $patientData = $responseData['metadata']['data'] ?? [];
    
                return [
                    'success' => true,
                    'data' => [
                        'code' => 200,
                        'message' => 'Success',
                        'data' => [
                            'nama_lengkap' => $patientData['nama_lengkap'] ?? null,
                            'nik' => $patientData['nik'] ?? null,
                            'jenis_kelamin' => $patientData['jenis_kelamin'] ?? null,
                            'tempat_lahir' => $patientData['tempat_lahir'] ?? null,
                            'tanggal_lahir' => $patientData['tanggal_lahir'] ?? null,
                            'alamat' => $patientData['alamat'] ?? null,
                            'status' => $patientData['status'] ?? null,
                            'no_rm' => $patientData['no_rekam_medis']?? null,
                        ]
                    ]
                ];
            } else {
                return [
                    'success' => false,
                    'data' => [
                        'code' => $responseData['metadata']['code'] ?? 500,
                        'message' => $responseData['metadata']['message'] ?? 'Unknown error',
                    ]
                ];
            }
        } else {
            return [
                'success' => false,
                'data' => [
                    'code' => $response->statusCode,
                    'message' => 'Error fetching patient status',
                ]
            ];
        }
    }

    public function actionTambahPasienBaru()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
    
        $token_core = Yii::$app->request->post('token_core');
        $find_user = User::find()->where(['auth_key' => $token_core])->one();
        if ($find_user === null) {
            return ['error' => true, 'message' => 'User not found with provided token'];
        }        
    
        $faskes_id = Yii::$app->request->post('faskes_id');
        $patientData = Yii::$app->request->post();
    
        if ($faskes_id === null) {
            return ['error' => true, 'message' => 'faskes_id is required'];
        }
    
        $endpoint = Endpoint::findOne(['faskes_id' => $faskes_id]);
        if (!$endpoint) {
            return ['error' => true, 'message' => 'Invalid faskes_id or endpoint not found'];
        }
    
        $url = $endpoint->url . '?r=mobile/service-post-umum/tambah-pasien-baru';
    
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl($url)
            ->setHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])
            ->setContent(json_encode([
                'nik' => $patientData['nik'],
                'nama_lengkap' => $patientData['nama_lengkap'],
                'jenis_kelamin' => $patientData['jenis_kelamin'],
                'tempat_lahir' => $patientData['tempat_lahir'],
                'tanggal_lahir' => $patientData['tanggal_lahir'],
                'alamat' => $patientData['alamat'],
                'ktp' => $patientData['ktp'],
            ]))
            ->send();
    
        $responseData = json_decode($response->content, true);
        if (isset($responseData['metadata']['data']['status'])){
            $model = new Personal();

            $model->nama_lengkap = $patientData['nama_lengkap'] ?? null;
            $model->jenis_kelamin = $patientData['jenis_kelamin'] ?? null;
            $model->tanggal_lahir = $patientData['tanggal_lahir'] ?? null;
            $model->tempat_lahir = $patientData['tempat_lahir'] ?? null;
            $model->nik = $patientData['nik'] ?? null;
            $model->user_id = $find_user ? $find_user->id : null;
            $model->status = $responseData['metadata']['data']['status'];
            $model->no_hp = "0";
            $model->is_delete = false;
            $model->relasi = 'Orang Lain';    
            if ($model->save()) {
                $rekam_medis= new Rekam_medis();
                $rekam_medis->personal_id = $model->id;
                $rekam_medis->faskes_id =$faskes_id;
                $rekam_medis->save();
                return [
                    'success' => true,
                    'data' => [
                        'code' => 201,
                        'message' => 'Profile Created',
                        'pasien' => $model->attributes
                    ]
                ];
            } else {
                return [
                    'error' => true,
                    'message' => 'Failed to save personal data',
                    'details' => $model->errors
                ];
            }
        } else {
            return [
                'error'=>true,
                'massage'=>'Gagal Menambahkan Data Pasien',
                'details'=>$responseData
            ];
        }
    }

    public function actionUpdateStatusPasien()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
    
        $token_core = Yii::$app->request->post('token_core');
        $faskes_id = Yii::$app->request->post('faskes_id');
        $username = Yii::$app->request->post('username');
        $nik = Yii::$app->request->post('nik');
    
        if (!$nik || !$token_core || !$faskes_id ) {
            return [
                'success' => false,
                'code' => 400,
                'message' => 'Missing required parameters',
            ];
        }
    
        $checkStatusResponse = $this->actionCheckStatusPasien();
    
        if (!$checkStatusResponse['success']) {
            return $checkStatusResponse;
        }
    
        $currentStatus = $checkStatusResponse['data']['data']['status'];
    
        if ($currentStatus === 'Menunggu Persetujuan') {
            return [
                'success' => false,
                'code' => 400,
                'message' => 'Belum disetujui oleh Faskes',
            ];
        }
    
        $find_user = User::find()->where(['auth_key' => $token_core])->one();
        if ($find_user === null) {
            return ['error' => true, 'message' => 'User not found with provided token'];
        }
    
        $model = Personal::find()
            ->where(['nik' => $nik, 'user_id' => $find_user->id])
            ->one();
    
        if (!$model) {
            return ['error' => true, 'message' => 'Personal data not found'];
        }
    
        $model->status = 'Sudah di ACC';
        $no_rm = $checkStatusResponse['data']['data']['no_rm'];
        $model->no_rm = strval($no_rm);

        $rekamMedis = Rekam_medis::find()
        ->where(['personal_id' => $model->id])
        ->one();

        if ($rekamMedis) {
            $rekamMedis->no_rm = strval($no_rm);
            if (!$rekamMedis->save()) {
                return [
                    'error' => true,
                    'message' => 'Failed to update Rekam Medis',
                    'details' => $rekamMedis->errors
                ];
            }
        }

        if ($model->save()) {
            return [
                'success' => true,
                'data' => [
                    'code' => 200,
                    'message' => 'Status updated successfully',
                    'pasien' => $model->attributes
                ]
            ];
        } else {
            return [
                'error' => true,
                'message' => 'Failed to update status',
                'details' => $model->errors
            ];
        }
    }
}
