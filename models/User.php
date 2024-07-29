<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\db\Expression;

class User extends ActiveRecord implements IdentityInterface
{
    public $password; // Attribute untuk input password

    public static function tableName()
    {
        return '{{%user}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function rules()
    {
        return [
            [['username', 'email', 'password'], 'required'],
            [['username', 'email'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['username', 'email'], 'unique'],
            ['status', 'default', 'value' => 10],
            ['type_user', 'default', 'value' => 1],
            ['status', 'in', 'range' => [10, 0]], // 10: active, 0: inactive
            ['type_user', 'in', 'range' => [1, 2]], // 1: regular user, 2: admin
            ['password', 'string', 'min' => 6],
            ['no_telepon', 'string', 'min' => 6],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'status' => 'Status',
            'type_user' => 'User Type',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = Yii::$app->security->generateRandomString();
            }
            if (!empty($this->password)) {
                $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
            }
            return true;
        }
        return false;
    }

    // Implementasi metode IdentityInterface
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }
}