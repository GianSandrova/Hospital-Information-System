<?php

namespace app\modules\profile\controllers;

use Yii;
use yii\rest\Controller;
use app\helpers\login_helper;
use yii\filters\auth\HttpBearerAuth;
use app\models\Personal;
use app\models\Rekam_medis;
use yii\web\NotFoundHttpException;
use yii\filters\Cors;

class RemoveController extends Controller
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

        $request = Yii::$app->request;
        $id = $request->post('id');

        if (!$id) {
            return [
                'success' => false,
                'code' => 400,
                'message' => 'ID is required',
            ];
        }

        $model = $this->findModel($id);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // First, delete related rekam_medis records
            Rekam_medis::deleteAll(['personal_id' => $id]);

            // Then, delete the personal record
            if ($model->delete()) {
                $transaction->commit();
                return [
                    'success' => true,
                    'code' => 200,
                    'message' => 'Profile and related records deleted successfully',
                ];
            } else {
                throw new \Exception('Failed to delete profile');
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return [
                'success' => false,
                'code' => 500,
                'message' => 'Failed to delete profile: ' . $e->getMessage(),
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