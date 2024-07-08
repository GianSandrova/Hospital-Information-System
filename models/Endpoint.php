<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%endpoint}}".
 *
 * @property int $id
 * @property int|null $faskes_id
 * @property string|null $name
 * @property string|null $url
 *
 * @property Faske $faskes
 */
class Endpoint extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%endpoint}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['faskes_id'], 'default', 'value' => null],
            [['faskes_id'], 'integer'],
            [['name', 'url'], 'string', 'max' => 255],
            [['faskes_id'], 'exist', 'skipOnError' => true, 'targetClass' => Faske::class, 'targetAttribute' => ['faskes_id' => 'id']],
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
            'name' => 'Name',
            'url' => 'Url',
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
}
