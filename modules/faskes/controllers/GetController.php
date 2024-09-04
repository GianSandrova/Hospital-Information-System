<?php

namespace app\modules\faskes\controllers;

use Yii;
use app\helpers\login_helper;
use yii\rest\Controller;
use app\models\Faskes;
use yii\filters\auth\HttpBearerAuth;
use yii\web\BadRequestHttpException;
use yii\filters\Cors;

class GetController extends Controller
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

    public function actionIndex($filter = null)
    {
        $header = Yii::$app->request->post();
        
        // $user = login_helper::findUser($header['no_telepon']);
        // if (!empty($user)) {
        //   $token = login_helper::getTokenMobile($user);
        //   if ($header['token_core'] == $token) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

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
                        'longitude' => $f->longtitud,
                        'latitude' => $f->latitude,
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
 }
}
