<?php

namespace app\models;

use Yii;

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
 *
 * @property RekamMedi[] $rekamMedis
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
            [['nama_lengkap', 'tanggal_lahir', 'tempat_lahir', 'no_hp', 'no_wa'], 'string', 'max' => 255],
            [['jenis_kelamin'], 'string', 'max' => 10],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'nik' => 'Nik',
            'nama_lengkap' => 'Nama Lengkap',
            'jenis_kelamin' => 'Jenis Kelamin',
            'tanggal_lahir' => 'Tanggal Lahir',
            'tempat_lahir' => 'Tempat Lahir',
            'no_hp' => 'No Hp',
            'no_wa' => 'No Wa',
            'is_delete' => 'Is Delete',
        ];
    }

    /**
     * Gets query for [[RekamMedis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRekamMedis()
    {
        return $this->hasMany(RekamMedi::class, ['personal_id' => 'id']);
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
}
