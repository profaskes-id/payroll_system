<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PengajuanShift;

/**
 * PengajuanShiftSearch represents the model behind the search form of `backend\models\PengajuanShift`.
 */
class PengajuanShiftSearch extends PengajuanShift
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pengajuan_shift', 'id_karyawan', 'id_shift_kerja', 'status', 'ditanggapi_oleh'], 'integer'],
            [['diajukan_pada', 'ditanggapi_pada', 'catatan_admin'], 'safe'],
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
        $query = PengajuanShift::find();

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
            'id_pengajuan_shift' => $this->id_pengajuan_shift,
            'id_karyawan' => $this->id_karyawan,
            'id_shift_kerja' => $this->id_shift_kerja,
            'diajukan_pada' => $this->diajukan_pada,
            'status' => $this->status,
            'ditanggapi_oleh' => $this->ditanggapi_oleh,
            'ditanggapi_pada' => $this->ditanggapi_pada,
        ]);

        $query->andFilterWhere(['like', 'catatan_admin', $this->catatan_admin]);

        return $dataProvider;
    }
}
