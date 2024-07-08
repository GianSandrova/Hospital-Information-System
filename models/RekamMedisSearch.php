<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Rekam_medis;

/**
 * RekamMedisSearch represents the model behind the search form of `app\models\Rekam_medis`.
 */
class RekamMedisSearch extends Rekam_medis
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'personal_id', 'faskes_id'], 'integer'],
            [['no_rm'], 'safe'],
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
        $query = Rekam_medis::find();

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
            'personal_id' => $this->personal_id,
            'faskes_id' => $this->faskes_id,
        ]);

        $query->andFilterWhere(['ilike', 'no_rm', $this->no_rm]);

        return $dataProvider;
    }
}
