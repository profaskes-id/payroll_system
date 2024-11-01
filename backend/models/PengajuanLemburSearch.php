<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PengajuanLembur;

/**
 * PengajuanLemburSearch represents the model behind the search form of `backend\models\PengajuanLembur`.
 */
class PengajuanLemburSearch extends PengajuanLembur
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pengajuan_lembur', 'id_karyawan', 'status', 'disetujui_oleh'], 'integer'],
            [['pekerjaan', 'jam_mulai', 'jam_selesai', 'tanggal'], 'safe'],
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
    public function search($params, $tgl_mulai, $tgl_selesai)
    {
        $query = PengajuanLembur::find()->where(['>=', 'tanggal', $tgl_mulai])->andWhere(['<=', 'tanggal', $tgl_selesai]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['status' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_pengajuan_lembur' => $this->id_pengajuan_lembur,
            'id_karyawan' => $this->id_karyawan,
            'status' => $this->status,
            'jam_mulai' => $this->jam_mulai,
            'jam_selesai' => $this->jam_selesai,
            'tanggal' => $this->tanggal,
            'disetujui_oleh' => $this->disetujui_oleh,
        ]);

        $query->andFilterWhere(['like', 'pekerjaan', $this->pekerjaan]);

        return $dataProvider;
    }
}
