<?php

namespace app\modules\faskes\controllers;

use Yii;
use app\helpers\login_helper;
use yii\rest\Controller;
use app\models\Faskes;
use yii\filters\auth\HttpBearerAuth;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;;
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

    public function actionFaskes()
    {
        $faskes = Faskes::find()->all();

        return $this->render('faskes', [
            'faskes' => $faskes,
        ]);
    }

    // Create Action
    public function actionCreate()
    {
        $faskes = new Faskes();

        if ($faskes->load(Yii::$app->request->post()) && $faskes->save()) {
            Yii::$app->session->setFlash('success', 'Data Faskes berhasil ditambahkan.');
            return $this->redirect(['faskes']);
        }

        return $this->render('create', [
            'faskes' => $faskes,
        ]);
    }

    // Update Action
    public function actionUpdate($id)
    {
        $faskes = $this->findModel($id);

        if ($faskes->load(Yii::$app->request->post()) && $faskes->save()) {
            Yii::$app->session->setFlash('success', 'Data Faskes berhasil diperbarui.');
            return $this->redirect(['faskes']);
        }

        return $this->render('update', [
            'faskes' => $faskes,
        ]);
    }

    // Delete Action
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        Yii::$app->session->setFlash('success', 'Data Faskes berhasil dihapus.');
        return $this->redirect(['faskes']);
    }

    // Find Model
    protected function findModel($id)
    {
        if (($model = Faskes::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
