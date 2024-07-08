<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Perjanjian;

/**
 * PerjanjianSearch represents the model behind the search form of `app\models\Perjanjian`.
 */
class PerjanjianSearch extends Perjanjian
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'rekam_medis_id', 'faskes_id'], 'integer'],
            [['kode_booking', 'poli', 'dokter', 'jadwal', 'waktu_perjanjian', 'no_antrian', 'status'], 'safe'],
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
        $query = Perjanjian::find();

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
            'rekam_medis_id' => $this->rekam_medis_id,
            'faskes_id' => $this->faskes_id,
            'waktu_perjanjian' => $this->waktu_perjanjian,
            'is_delete' => $this->is_delete,
        ]);

        $query->andFilterWhere(['ilike', 'kode_booking', $this->kode_booking])
            ->andFilterWhere(['ilike', 'poli', $this->poli])
            ->andFilterWhere(['ilike', 'dokter', $this->dokter])
            ->andFilterWhere(['ilike', 'jadwal', $this->jadwal])
            ->andFilterWhere(['ilike', 'no_antrian', $this->no_antrian])
            ->andFilterWhere(['ilike', 'status', $this->status]);

        return $dataProvider;
    }
}
