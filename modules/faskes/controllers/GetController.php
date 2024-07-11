<?php

namespace app\modules\faskes\controllers;

use Yii;
use yii\rest\Controller;
use app\models\Faskes;
use yii\filters\auth\HttpBearerAuth;
use yii\web\BadRequestHttpException;

class GetController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        return $behaviors;
    }

    public function actionIndex($filter = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Check token validation
        if (!Yii::$app->user->isGuest) {
            // Fetch data
            $query = Faskes::find();

            if ($filter) {
                $query->andWhere(['like', 'nama_faskes', $filter]);
            }

            $faskes = $query->all();

            if ($faskes) {
                $data = [];
                foreach ($faskes as $f) {
                    $data[] = [
                        'id' => $f->id,
                        'nama_faskes' => $f->nama_faskes,
                        'alamat' => $f->alamat,
                        'deskripsi' => $f->deskripsi,
                        'logo' => $f->logo,
                        'longitud' => $f->longtitud,
                        'latitud' => $f->latitude,
                    ];
                }

                return [
                    'code' => 200,
                    'message' => 'Faskes',
                    'data' => $data,
                ];
            } else {
                return [
                    'code' => 404,
                    'message' => 'Faskes Tidak Ditemukan',
                ];
            }
        } else {
            return [
                'code' => 401,
                'message' => 'Token Expired',
            ];
        }
    }
}

