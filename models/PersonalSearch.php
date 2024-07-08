<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Personal;

/**
 * PersonalSearch represents the model behind the search form of `app\models\Personal`.
 */
class PersonalSearch extends Personal
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['nik', 'nama_lengkap', 'jenis_kelamin', 'tanggal_lahir', 'tempat_lahir', 'no_hp', 'no_wa'], 'safe'],
            [['is_delete'], 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Personal::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'is_delete' => $this->is_delete,
        ]);

        $query->andFilterWhere(['ilike', 'nik', $this->nik])
            ->andFilterWhere(['ilike', 'nama_lengkap', $this->nama_lengkap])
            ->andFilterWhere(['ilike', 'jenis_kelamin', $this->jenis_kelamin])
            ->andFilterWhere(['ilike', 'tanggal_lahir', $this->tanggal_lahir])
            ->andFilterWhere(['ilike', 'tempat_lahir', $this->tempat_lahir])
            ->andFilterWhere(['ilike', 'no_hp', $this->no_hp])
            ->andFilterWhere(['ilike', 'no_wa', $this->no_wa]);

        return $dataProvider;
    }
}
