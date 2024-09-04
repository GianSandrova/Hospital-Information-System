<?php

namespace app\helpers;

use app\models\User;

class login_helper{
    public static function getTokenMobile($user)
    {
        $header_encode = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode(['no_handphone' => $user->username, 'date' => strtotime(date('Y-m-d')) * 1000]);
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header_encode));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, 'abC123!', true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        $token = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
        return $token;
    }
    public static function findUser($username){
        return User::find()->where(['username'=>$username])->one();
    }
}