<?php
namespace app\modules\akun\controllers;

use Yii;
use yii\rest\Controller;
use app\models\User;
use yii\web\UnauthorizedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;

class ChangePasswordController extends Controller
{
    public function actionIndex($userId)
    {
        $token = Yii::$app->request->headers->get('Authorization');
        $password = Yii::$app->request->post('password');
        $rePassword = Yii::$app->request->post('re-password');
        $oldPassword = Yii::$app->request->post('old-password');

        if (!$token) {
            return [
                'code' => 401,
                'message' => 'Token Expired'
            ];
        }

        // Remove 'Bearer ' prefix if present
        $token = str_replace('Bearer ', '', $token);

        $user = User::findOne($userId);

        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }

        // Verify token
        if ($user->auth_key !== $token) {
            return [
                'code' => 403,
                'message' => 'Tidak Memiliki Akses'
            ];
        }


        // Verify old password
        if (!Yii::$app->security->validatePassword($oldPassword, $user->password_hash)) {
            return [
                'code' => 402,
                'message' => 'Password Lama Tidak Cocok'
            ];
        }

        // Verify new passwords match
        if ($password !== $rePassword) {
            return [
                'code' => 402,
                'message' => 'Password Tidak Cocok dengan password Utama'
            ];
        }

        // Change password
        $user->password_hash = Yii::$app->security->generatePasswordHash($password);
        $user->auth_key = Yii::$app->security->generateRandomString(); // Generate new auth_key
        
        // Save new password
        if ($user->save(false)) {
            return [
                'code' => 200,
                'message' => 'Sukses Ubah Password',
                'data' => [
                    'userId' => $user->id,
                    'username' => $user->username,
                ]
            ];
        } else {
            throw new BadRequestHttpException('Gagal mengubah password');
        }
    }
}