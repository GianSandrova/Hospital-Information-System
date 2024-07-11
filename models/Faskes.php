<?php

namespace app\models;

use Yii;

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
            [['nama_faskes', 'alamat', 'logo', 'user_api', 'password_api', 'longtitud', 'latitude'], 'string', 'max' => 255],
            [['ip_address'], 'string', 'max' => 15],
        ];
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
        return $this->hasMany(RekamMedis::class, ['faskes_id' => 'id']);
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
