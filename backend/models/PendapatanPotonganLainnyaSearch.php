<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PendapatanPotonganLainnya;

/**
 * PendapatanPotonganLainnyaSearch represents the model behind the search form of `backend\models\PendapatanPotonganLainnya`.
 */
class PendapatanPotonganLainnyaSearch extends PendapatanPotonganLainnya
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_ppl', 'id_karyawan', 'bulan', 'tahun', 'is_pendapatan', 'is_potongan', 'created_at', 'updated_at', 'jumlah'], 'integer'],
            [['keterangan'], 'safe'],
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
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = PendapatanPotonganLainnya::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_ppl' => $this->id_ppl,
            'id_karyawan' => $this->id_karyawan,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'is_pendapatan' => $this->is_pendapatan,
            'is_potongan' => $this->is_potongan,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'jumlah' => $this->jumlah,
        ]);

        $query->andFilterWhere(['like', 'keterangan', $this->keterangan]);

        return $dataProvider;
    }
}
