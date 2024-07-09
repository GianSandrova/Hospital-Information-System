<?php

namespace app\modules\profile\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use app\models\Personal;
use yii\web\NotFoundHttpException;

class RemoveController extends Controller
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
        $id = $request->post('id');

        if (!$id) {
            return [
                'code' => 400,
                'message' => 'ID is required',
            ];
        }

        $model = $this->findModel($id);

        if ($model->user_id !== Yii::$app->user->id) {
            return [
                'code' => 403,
                'message' => 'You are not allowed to delete this profile',
            ];
        }

        if ($model->delete()) {
            return [
                'code' => 200,
                'message' => 'Profile Deleted',
            ];
        } else {
            return [
                'code' => 412,
                'message' => 'Profile Deleted Failed',
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