<?php

namespace app\modules\akun\controllers;

use Yii;
use yii\rest\ActiveController;
use app\models\User;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;

class RegistrasiController extends ActiveController
{
    public $modelClass = 'app\models\User';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        return $actions;
    }


    public function actionCreate()
    {
        $model = new User();

        $data = Yii::$app->getRequest()->getBodyParams();
        
        // Memastikan hanya atribut yang diizinkan yang diproses
        $safeAttributes = ['username', 'email', 'password','no_telepon'];
        foreach ($data as $key => $value) {
            if (!in_array($key, $safeAttributes)) {
                unset($data[$key]);
            }
        }

        if ($model->load($data, '') && $model->validate()) {
            if ($model->save()) {
                Yii::$app->response->setStatusCode(201);
                return [
                    'status' => 'success',
                    'message' => 'User registered successfully',
                    'data' => [
                        'id' => $model->id,
                        'username' => $model->username,
                        'email' => $model->email,
                        'no_telepon'=> $model -> no_telepon,
                    ]
                ];
            } else {
                Yii::$app->response->setStatusCode(500);
                return [
                    'status' => 'error',
                    'message' => 'Failed to save user',
                    'errors' => $model->errors
                ];
            }
        } else {
            Yii::$app->response->setStatusCode(422);
            return [
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $model->errors
            ];
        }
    }
}