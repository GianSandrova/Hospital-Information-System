<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%perjanjian}}".
 *
 * @property int $id
 * @property int|null $rekam_medis_id
 * @property int|null $faskes_id
 * @property string|null $kode_booking
 * @property string|null $poli
 * @property string|null $dokter
 * @property string|null $jadwal
 * @property string|null $waktu_perjanjian
 * @property string|null $no_antrian
 * @property string|null $status
 * @property bool|null $is_delete
 *
 * @property Faske $faskes
 * @property RekamMedi $rekamMedis
 */
class Perjanjian extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%perjanjian}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rekam_medis_id', 'faskes_id'], 'default', 'value' => null],
            [['rekam_medis_id', 'faskes_id'], 'integer'],
            [['waktu_perjanjian'], 'safe'],
            [['is_delete'], 'boolean'],
            [['kode_booking', 'poli', 'dokter', 'jadwal', 'status'], 'string', 'max' => 255],
            [['no_antrian'], 'string', 'max' => 5],
            [['faskes_id'], 'exist', 'skipOnError' => true, 'targetClass' => Faske::class, 'targetAttribute' => ['faskes_id' => 'id']],
            [['rekam_medis_id'], 'exist', 'skipOnError' => true, 'targetClass' => RekamMedi::class, 'targetAttribute' => ['rekam_medis_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rekam_medis_id' => 'Rekam Medis ID',
            'faskes_id' => 'Faskes ID',
            'kode_booking' => 'Kode Booking',
            'poli' => 'Poli',
            'dokter' => 'Dokter',
            'jadwal' => 'Jadwal',
            'waktu_perjanjian' => 'Waktu Perjanjian',
            'no_antrian' => 'No Antrian',
            'status' => 'Status',
            'is_delete' => 'Is Delete',
        ];
    }

    /**
     * Gets query for [[Faskes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFaskes()
    {
        return $this->hasOne(Faske::class, ['id' => 'faskes_id']);
    }

    /**
     * Gets query for [[RekamMedis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRekamMedis()
    {
        return $this->hasOne(RekamMedi::class, ['id' => 'rekam_medis_id']);
    }
}
