<?php

namespace app\modules\akun\controllers;

use Yii;
use yii\rest\Controller;
use app\models\User;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;
use yii\filters\Cors;
use yii\helpers\Url;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\RawMessage;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;


class ForgetPasswordController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ['http://localhost:3000'], 
                'Access-Control-Request-Method' => ['POST'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 86400,
            ],
        ];
        return $behaviors;
    }

    public function actionRequestPasswordReset()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        // Konfigurasi transport dan mailer
        $transport = Transport::fromDsn('smtp://gian.sandrova@gmail.com:wfdkiqpcsmhxxznm@smtp.gmail.com:587');
        $mailer = new Mailer($transport);
        
        $email = Yii::$app->request->post('email');
        $user = User::findOne(['email' => $email]);
    
        if (!$user) {
            throw new BadRequestHttpException('User with this email does not exist.');
        }
    
        $resetToken = Yii::$app->security->generateRandomString(32);
        $user->password_reset_token = $resetToken;
        $user->password_reset_token_expire = time() + 3600; // Token valid for 1 hour
    
        if ($user->save(false)) {
            // Generate the reset link
            $resetLink = 'http://localhost:3000/reset-password?lp=' . urlencode($resetToken);
            // Membuat email
            $emaill = (new Email())
                ->from('gian.sandrova@gmail.com')  // Ganti dengan alamat emailmu
                ->to($user->email)  // Mengirim ke email user yang meminta reset
                ->subject('Password Reset Request')
                ->text("Click the link below to reset your password:\n$resetLink")
                ->html("<p>Click the link below to reset your password:</p><p><a href=\"$resetLink\">$resetLink</a></p>");
    
            // Mengirim email
            try {
                $mailer->send($emaill);
                return ['status' => 'success', 'message' => 'Password reset email sent.'];
            } catch (\Exception $e) {
                throw new ServerErrorHttpException('Failed to send password reset email.');
            }
        }
    
        throw new ServerErrorHttpException('Failed to generate password reset token.');
    }
    

    public function actionResetPassword()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $token = Yii::$app->request->post('token');
        $newPassword = Yii::$app->request->post('newPassword');

        $user = User::findOne(['password_reset_token' => $token]);

        if (!$user || $user->password_reset_token_expire < time()) {
            throw new BadRequestHttpException('Invalid or expired password reset token.');
        }

        $user->setPassword($newPassword);
        $user->removePasswordResetToken();

        if ($user->save(false)) {
            return [
                'status' => 'success',
                'message' => 'Your password has been successfully reset.'
            ];
        } else {
            throw new ServerErrorHttpException('Failed to reset password.');
        }
    }
}