<?php
namespace app\modules\akun\controllers;

use Yii;
use yii\rest\Controller;
use app\models\User;
use yii\web\UnauthorizedHttpException;
use app\models\UserSearch;
use yii\filters\Cors;

class LoginController extends Controller
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
        $email = Yii::$app->request->post('email');
        $password = Yii::$app->request->post('password');

        $user = User::findOne(['email' => $email]);

        if (!$user || !Yii::$app->security->validatePassword($password, $user->password_hash)) {
            throw new UnauthorizedHttpException('Invalid email or password.');
        }

        // Generate access token
        $user->auth_key = Yii::$app->security->generateRandomString();
        
        // Update last_login, time_login, and ip_login
        $user->last_login = date('Y-m-d H:i:s');
        $user->time_login = date('Y-m-d H:i:s');
        $user->ip_login = Yii::$app->request->userIP;
        
        // Save user data
        $user->save(false);

        return [
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'access_token' => $user->auth_key,
            ]
        ];
    }

    public function actionTampil()
    {
        // $searchModel = new UserSearch();
        // $dataProvider = $searchModel->search($this->request->queryParams);
    
        \Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
        // echo "<h1>Debug: Before Render</h1>";
        return $this->render('index');
    }
}
