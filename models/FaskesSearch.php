<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Faskes;

/**
 * FaskesSearch represents the model behind the search form of `app\models\Faskes`.
 */
class FaskesSearch extends Faskes
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['client_id', 'nama_faskes', 'alamat', 'deskripsi', 'logo', 'ip_address', 'user_api', 'password_api', 'longtitud', 'latitude'], 'safe'],
            [['is_aktif', 'is_bridging'], 'boolean'],
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
        $query = Faskes::find();

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
            'is_aktif' => $this->is_aktif,
            'is_bridging' => $this->is_bridging,
        ]);

        $query->andFilterWhere(['ilike', 'client_id', $this->client_id])
            ->andFilterWhere(['ilike', 'nama_faskes', $this->nama_faskes])
            ->andFilterWhere(['ilike', 'alamat', $this->alamat])
            ->andFilterWhere(['ilike', 'deskripsi', $this->deskripsi])
            ->andFilterWhere(['ilike', 'logo', $this->logo])
            ->andFilterWhere(['ilike', 'ip_address', $this->ip_address])
            ->andFilterWhere(['ilike', 'user_api', $this->user_api])
            ->andFilterWhere(['ilike', 'password_api', $this->password_api])
            ->andFilterWhere(['ilike', 'longtitud', $this->longtitud])
            ->andFilterWhere(['ilike', 'latitude', $this->latitude]);

        return $dataProvider;
    }
}
