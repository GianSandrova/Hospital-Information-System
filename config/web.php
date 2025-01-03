<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'v1' => [
            'class' => 'app\modules\v1\Module',
        ],
        'jadwal' => [
            'class' => 'app\modules\jadwal\Module',
        ], 
        'akun' => [
            'class' => 'app\modules\akun\Module',
        ],
        'profile' => [
            'class' => 'app\modules\profile\Module',
        ],
        'faskes' => [
            'class' => 'app\modules\faskes\Module',
        ],
        'medicalrecord' => [
            'class' => 'app\modules\medicalrecord\Module',
        ],
        'appointment' => [
            'class' => 'app\modules\appointment\Module',
        ],

    ],
    'components' => [

        'mailer' => [
            'class' => 'yii\symfonymailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'dsn' => 'smtp://gian.sandrova@gmail.com:wfdkiqpcsmhxxznm@smtp.gmail.com:587',
            ],
        ],    

        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'A9CJxRx157T0AqR9bf5d779b_aYDSaT5',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['akun/registrasi'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'POST create' => 'create',
                        'PUT,PATCH {id}' => 'update',
                    ],
                ],
                'akun/change-password/<userId:\d+>' => 'akun/change-password/index',
                'PUT profile/update/<id:\d+>' => 'profile/update/index',
                'GET medicalrecord/get-profile/<profileId:\d+>' => 'medicalrecord/get-profile/index',
                'GET profile/<id:\d+>' => 'profile/get-by-id/index',
            ],
        ],
        
    ],
    'params' => $params,
    'defaultRoute' => 'site/login',
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
