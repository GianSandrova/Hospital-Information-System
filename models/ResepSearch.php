<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Resep;

/**
 * ResepSearch represents the model behind the search form of `app\models\Resep`.
 */
class ResepSearch extends Resep
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'faskes_id', 'rekam_medis_id', 'jumlah', 'jumlah_diminum', 'frekuensi'], 'integer'],
            [['kode_booking', 'nama_obat', 'signa', 'terakhir_minum', 'minum_berikutnya'], 'safe'],
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
        $query = Resep::find();

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
            'faskes_id' => $this->faskes_id,
            'rekam_medis_id' => $this->rekam_medis_id,
            'jumlah' => $this->jumlah,
            'jumlah_diminum' => $this->jumlah_diminum,
            'frekuensi' => $this->frekuensi,
            'terakhir_minum' => $this->terakhir_minum,
            'minum_berikutnya' => $this->minum_berikutnya,
        ]);

        $query->andFilterWhere(['ilike', 'kode_booking', $this->kode_booking])
            ->andFilterWhere(['ilike', 'nama_obat', $this->nama_obat])
            ->andFilterWhere(['ilike', 'signa', $this->signa]);

        return $dataProvider;
    }
}
