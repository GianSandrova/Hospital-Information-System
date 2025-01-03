<?php

namespace app\modules\profile\controllers;

use Yii;
use app\helpers\login_helper;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use app\models\Personal;
use yii\web\NotFoundHttpException;
use yii\filters\Cors;

class GetAllController extends Controller
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
        $header = Yii::$app->request->post();
        if (!isset($header['token_core'])) {
            return [
                'success' => false,
                'code' => 400,
                'message' => 'Missing required parameters',
            ];
        }
    
        $user = login_helper::findUser($header['username']);
        if (empty($user)) {
            return [
                'success' => false,
                'code' => 404,
                'message' => 'User not found',
            ];
        }
    
        $token = login_helper::getTokenMobile($user);
        if ($header['token_core'] !== $token) {
            return [
                'success' => false,
                'code' => 401,
                'message' => 'Invalid token',
            ];
        }
    
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    
        // Menambahkan kondisi untuk mengecualikan status 'Menunggu Persetujuan'
        $profiles = Personal::find()
            ->where(['user_id' => $user->id])
            ->andWhere(['!=', 'status', 'Menunggu Persetujuan'])  // Kondisi tambahan
            ->all();
    
        if (empty($profiles)) {
            return [
                'success' => false,
                'code' => 404,
                'message' => 'No profiles found for this user',
            ];
        }
    
        $profileData = [];
        foreach ($profiles as $profile) {
            $profileData[] = [
                'id' => $profile->id,
                'user_id' => $profile->user_id,
                'no_rm' => $profile->no_rm,
                'relasi' => $profile->relasi,
                'nik' => $profile->nik,
                'nama_lengkap' => $profile->nama_lengkap,
                'jenis_kelamin' => $profile->jenis_kelamin,
                'tanggal_lahir' => $profile->tanggal_lahir,
                'tempat_lahir' => $profile->tempat_lahir,
                'no_hp' => $profile->no_hp,
                'no_wa' => $profile->no_wa,
                'email' => $profile->email,
            ];
        }
    
        return [
            'code' => 200,
            'message' => 'Success',
            'data' => $profileData,
        ];
    }

    public function actionPendingApproval()
    {
        $header = Yii::$app->request->post();
        if (!isset($header['token_core'])) {
            return [
                'success' => false,
                'code' => 400,
                'message' => 'Missing required parameters',
            ];
        }

        $user = login_helper::findUser($header['username']);
        if (empty($user)) {
            return [
                'success' => false,
                'code' => 404,
                'message' => 'User not found',
            ];
        }

        $token = login_helper::getTokenMobile($user);
        if ($header['token_core'] !== $token) {
            return [
                'success' => false,
                'code' => 401,
                'message' => 'Invalid token',
            ];
        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Mengambil data dengan status 'Menunggu Persetujuan'
        $profiles = Personal::find()
            ->where(['user_id' => $user->id])
            ->andWhere(['status' => 'Menunggu Persetujuan'])  // Kondisi status 'Menunggu Persetujuan'
            ->all();

        if (empty($profiles)) {
            return [
                'success' => false,
                'code' => 404,
                'message' => 'No profiles found with pending approval status',
            ];
        }

        $profileData = [];
        foreach ($profiles as $profile) {
            $profileData[] = [
                'id' => $profile->id,
                'user_id' => $profile->user_id,
                'no_rm' => $profile->no_rm,
                'relasi' => $profile->relasi,
                'nik' => $profile->nik,
                'nama_lengkap' => $profile->nama_lengkap,
                'jenis_kelamin' => $profile->jenis_kelamin,
                'tanggal_lahir' => $profile->tanggal_lahir,
                'tempat_lahir' => $profile->tempat_lahir,
                'no_hp' => $profile->no_hp,
                'no_wa' => $profile->no_wa,
                'email' => $profile->email,
            ];
        }

        return [
            'code' => 200,
            'message' => 'Success',
            'data' => $profileData,
        ];
    }
    
}