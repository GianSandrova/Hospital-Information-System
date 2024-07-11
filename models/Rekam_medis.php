<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%rekam_medis}}".
 *
 * @property int $id
 * @property int|null $personal_id
 * @property int|null $faskes_id
 * @property string|null $no_rm
 *
 * @property Faskes $faskes
 * @property Perjanjian[] $perjanjians
 * @property Personal $personal
 * @property Resep[] $reseps
 */
class Rekam_medis extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%rekam_medis}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['personal_id', 'faskes_id'], 'default', 'value' => null],
            [['personal_id', 'faskes_id'], 'integer'],
            [['no_rm'], 'string', 'max' => 255],
            [['faskes_id'], 'exist', 'skipOnError' => true, 'targetClass' => Faskes::class, 'targetAttribute' => ['faskes_id' => 'id']],
            [['personal_id'], 'exist', 'skipOnError' => true, 'targetClass' => Personal::class, 'targetAttribute' => ['personal_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'personal_id' => 'Personal ID',
            'faskes_id' => 'Faskes ID',
            'no_rm' => 'No Rm',
        ];
    }

    /**
     * Gets query for [[Faskes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFaskes()
    {
        return $this->hasOne(Faskes::class, ['id' => 'faskes_id']);
    }

    /**
     * Gets query for [[Perjanjians]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPerjanjians()
    {
        return $this->hasMany(Perjanjian::class, ['rekam_medis_id' => 'id']);
    }

    /**
     * Gets query for [[Personal]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPersonal()
    {
        return $this->hasOne(Personal::class, ['id' => 'personal_id']);
    }

    /**
     * Gets query for [[Reseps]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReseps()
    {
        return $this->hasMany(Resep::class, ['rekam_medis_id' => 'id']);
    }
}
