<?php

namespace app\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%faskes}}".
 *  
 * @property int $id
 * @property string|null $client_id
 * @property string|null $nama_faskes
 * @property string|null $alamat
 * @property string|null $deskripsi
 * @property string|null $logo
 * @property bool|null $is_aktif
 * @property bool|null $is_bridging
 * @property string|null $ip_address
 * @property string|null $user_api
 * @property string|null $password_api
 * @property string|null $longtitud
 * @property string|null $latitude
 *
 * @property Endpoint[] $endpoints
 * @property Perjanjian[] $perjanjians
 * @property RekamMedis[] $rekamMedis
 * @property Resep[] $reseps
 */
class Faskes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%faskes}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['deskripsi'], 'string'],
            [['is_aktif', 'is_bridging'], 'boolean'],
            [['client_id'], 'string', 'max' => 20],
            [['nama_faskes', 'alamat', 'logo', 'user_api', 'password_api', 'longtitud', 'latitude','token_mobile'], 'string', 'max' => 255],
            [['ip_address'], 'string', 'max' => 15],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'client_id',
                ],
                'value' => function ($event) {
                    return $this->generateClientId();
                },
            ],
        ];
    }

    private function generateClientId()
    {
        $lastFaskes = self::find()->orderBy(['id' => SORT_DESC])->one();
        if ($lastFaskes) {
            $lastClientId = $lastFaskes->client_id;
            $number = intval(substr($lastClientId, 6)) + 1;
            return 'CLIENT' . str_pad($number, 3, '0', STR_PAD_LEFT);
        }
        return 'CLIENT001';
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_id' => 'Client ID',
            'nama_faskes' => 'Nama Faskes',
            'alamat' => 'Alamat',
            'deskripsi' => 'Deskripsi',
            'logo' => 'Logo',
            'is_aktif' => 'Is Aktif',
            'is_bridging' => 'Is Bridging',
            'ip_address' => 'Ip Address',
            'user_api' => 'User Api',
            'password_api' => 'Password Api',
            'longtitud' => 'Longtitud',
            'latitude' => 'Latitude',
        ];
    }

    /**
     * Gets query for [[Endpoints]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEndpoints()
    {
        return $this->hasMany(Endpoint::class, ['faskes_id' => 'id']);
    }

    /**
     * Gets query for [[Perjanjians]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPerjanjians()
    {
        return $this->hasMany(Perjanjian::class, ['faskes_id' => 'id']);
    }

    /**
     * Gets query for [[RekamMedis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRekamMedis()
    {
        return $this->hasMany(Rekam_medis::class, ['faskes_id' => 'id']);
    }

    /**
     * Gets query for [[Reseps]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReseps()
    {
        return $this->hasMany(Resep::class, ['faskes_id' => 'id']);
    }
}
