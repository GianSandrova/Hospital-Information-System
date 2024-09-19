<?php
namespace app\modules\akun\controllers;

use Yii;
use yii\rest\Controller;
use app\models\User;
use yii\web\UnauthorizedHttpException;
use app\models\UserSearch;
use yii\filters\Cors;
use yii\httpclient\Client;
use yii\web\Response;
use app\models\Endpoint;
use app\models\Faskes;
use app\helpers\login_helper;

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

    public function actionLogin()
    {
       // Yii::$app->response->format = Response::FORMAT_JSON;
        $email = Yii::$app->request->post('email');
        $password = Yii::$app->request->post('password');

        $user = User::findOne(['email' => $email]);

        if (!$user || !Yii::$app->security->validatePassword($password, $user->password_hash)) {
            throw new UnauthorizedHttpException('Invalid email or password.');
        }

        // Generate access token
        $user->auth_key = login_helper::getTokenMobile($user);
        
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

  public function actionKlinikLogin()
  {
    Yii::$app->response->format = Response::FORMAT_JSON;

    $faskes_id = Yii::$app->request->post('faskes_id');

    $faskes = Faskes::findOne($faskes_id);
    if (!$faskes) {
      return ['error' => true, 'message' => 'Invalid faskes_id'];
    }

    $endpoint = Endpoint::findOne(['faskes_id' => $faskes_id]);
    if (!$endpoint) {
      return ['error' => true, 'message' => 'Endpoint not found for this faskes'];
    }

    // Login to Emesys Clinic
    $client = new Client();
    $response = $client->createRequest()
      ->setMethod('POST')
      ->setUrl($endpoint->url . '?r=mobile/service-post-umum/login')
      ->setHeaders(['Content-Type' => 'application/json'])
      ->setContent(json_encode([
        'username' => $faskes->user_api,
        'password' =>strval($faskes->password_api) ,
      ]))
      ->send();

    if (!$response->isOk) {
      throw new UnauthorizedHttpException('Failed to authenticate with Emesys Clinic.');
    }

    $data = json_decode(json_encode($response->data));
    $faskes->token_mobile = $data->response->token;
    $faskes->save(false);

    return [
      'status' => 'success',
      'message' => 'Faskes sudah dipilih dan login ke klinik berhasil',
      // 'username'=>$faskes->user_api,
      // 'password'=>$faskes->password_api,
      // 'keterangan'=>$faskes->endpo->keterangan,
      // 'id_faskes'=>$faskes_id,
      // 'clientid'=>$faskes->client_id,
      // 'url'=>$faskes->endpo->url,
      'data'=>$data,
      // $data,
    ];
  }

    public function actionAdminLogin()
    {
        $email = Yii::$app->request->post('email');
        $password = Yii::$app->request->post('password');
    
        $user = User::findOne(['email' => $email]);
    
        if (!$user || !Yii::$app->security->validatePassword($password, $user->password_hash)) {
            Yii::$app->session->setFlash('error', 'Invalid email or password.');
            return $this->redirect(['/']);
        }
    
        if ($user->type_user != 2) {
            Yii::$app->session->setFlash('error', 'Hanya admin yang bisa login');
            return $this->redirect(['/']);
        }
    
        // Generate access token
        $user->auth_key = login_helper::getTokenMobile($user);
        
        // Update last_login, time_login, and ip_login
        $user->last_login = date('Y-m-d H:i:s');
        $user->time_login = date('Y-m-d H:i:s');
        $user->ip_login = Yii::$app->request->userIP;
        
        // Save user data
        $user->save(false);
    
        // Redirect to dashboard
        return $this->redirect(['/site/dashboard']);
    }
}
