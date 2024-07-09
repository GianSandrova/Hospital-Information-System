<?php

namespace app\modules\profile\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use app\models\Personal;
use yii\web\NotFoundHttpException;

class UpdateController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        return $behaviors;
    }

    public function actionIndex($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = $this->findModel($id);

        if ($model->user_id !== Yii::$app->user->id) {
            return [
                'code' => 403,
                'message' => 'You are not allowed to update this profile',
            ];
        }

        $model->load(Yii::$app->request->getBodyParams(), '');

        if ($model->save()) {
            return [
                'code' => 200,
                'message' => 'Profile Updated',
                'data' => [
                    'id' => $model->id,
                    'user_id' => $model->user_id,
                    'relasi' => $model->relasi,
                    'nik' => $model->nik,
                    'nama_lengkap' => $model->nama_lengkap,
                    'jenis_kelamin' => $model->jenis_kelamin,
                    'tanggal_lahir' => $model->tanggal_lahir,
                    'no_hp' => $model->no_hp,
                    'no_wa' => $model->no_wa,
                    'email' => $model->email,
                ],
            ];
        } else {
            return [
                'code' => 422,
                'message' => 'Validation Error',
                'errors' => $model->errors,
            ];
        }
    }

    protected function findModel($id)
    {
        if (($model = Personal::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested profile does not exist.');
    }
}