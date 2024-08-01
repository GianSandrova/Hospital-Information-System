<?php

namespace app\models;

use Yii;
use yii\validators\Validator;

/**
 * This is the model class for table "{{%personal}}".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $nik
 * @property string|null $nama_lengkap
 * @property string|null $jenis_kelamin
 * @property string|null $tanggal_lahir
 * @property string|null $tempat_lahir
 * @property string|null $no_hp
 * @property string|null $no_wa
 * @property bool|null $is_delete
 * @property string|null $relasi
 * @property string|null $email
 *
 * @property Rekam_medis[] $rekamMedis
 * @property User $user
 */
class Personal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%personal}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'default', 'value' => null],
            [['user_id'], 'integer'],
            [['is_delete'], 'boolean'],
            [['nik'], 'string', 'max' => 16],
            [['nik'], 'match', 'pattern' => '/^[0-9]{16}$/', 'message' => 'NIK harus 16 karakter berupa angka'],
            [['nama_lengkap', 'tanggal_lahir', 'tempat_lahir', 'no_hp', 'no_wa', 'relasi', 'email'], 'string', 'max' => 255],
            [['jenis_kelamin'], 'string', 'max' => 10],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['nik', 'nama_lengkap', 'jenis_kelamin', 'tanggal_lahir', 'no_hp', 'no_wa', 'relasi', 'email'], 'required'],
            [['email'], 'email'],
            [['no_hp', 'no_wa'], 'match', 'pattern' => '/^[0-9]+$/', 'message' => 'Hanya boleh berisi angka'],
            [['tanggal_lahir'], 'date', 'format' => 'php:Y-m-d'],
            [['relasi'], 'validateRelasi'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'nik' => 'NIK',
            'nama_lengkap' => 'Nama Lengkap',
            'jenis_kelamin' => 'Jenis Kelamin',
            'tanggal_lahir' => 'Tanggal Lahir',
            'tempat_lahir' => 'Tempat Lahir',
            'no_hp' => 'No HP',
            'no_wa' => 'No WA',
            'is_delete' => 'Is Delete',
            'relasi' => 'Relasi',
            'email' => 'Email',
        ];
    }

    /**
     * Gets query for [[RekamMedis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRekamMedis()
    {
        return $this->hasMany(Rekam_medis::class, ['personal_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Validates the relasi attribute.
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateRelasi($attribute, $params)
    {
        if ($this->$attribute === 'Diri Sendiri') {
            $existingProfile = self::findOne(['user_id' => $this->user_id, 'relasi' => 'Diri Sendiri']);
            if ($existingProfile && $existingProfile->id !== $this->id) {
                $this->addError($attribute, 'Hanya boleh ada satu profil dengan relasi "Diri Sendiri" per user.');
            }
        }
    }
}