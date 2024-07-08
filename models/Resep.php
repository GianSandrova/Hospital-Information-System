<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%resep}}".
 *
 * @property int $id
 * @property int|null $faskes_id
 * @property int|null $rekam_medis_id
 * @property string|null $kode_booking
 * @property string|null $nama_obat
 * @property string|null $signa
 * @property int|null $jumlah
 * @property int|null $jumlah_diminum
 * @property int|null $frekuensi
 * @property string|null $terakhir_minum
 * @property string|null $minum_berikutnya
 *
 * @property Faske $faskes
 * @property RekamMedi $rekamMedis
 */
class Resep extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%resep}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['faskes_id', 'rekam_medis_id', 'jumlah', 'jumlah_diminum', 'frekuensi'], 'default', 'value' => null],
            [['faskes_id', 'rekam_medis_id', 'jumlah', 'jumlah_diminum', 'frekuensi'], 'integer'],
            [['terakhir_minum', 'minum_berikutnya'], 'safe'],
            [['kode_booking', 'nama_obat', 'signa'], 'string', 'max' => 255],
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
            'faskes_id' => 'Faskes ID',
            'rekam_medis_id' => 'Rekam Medis ID',
            'kode_booking' => 'Kode Booking',
            'nama_obat' => 'Nama Obat',
            'signa' => 'Signa',
            'jumlah' => 'Jumlah',
            'jumlah_diminum' => 'Jumlah Diminum',
            'frekuensi' => 'Frekuensi',
            'terakhir_minum' => 'Terakhir Minum',
            'minum_berikutnya' => 'Minum Berikutnya',
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
