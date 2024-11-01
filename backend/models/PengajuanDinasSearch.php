<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PengajuanDinas;

/**
 * PengajuanDinasSearch represents the model behind the search form of `backend\models\PengajuanDinas`.
 */
class PengajuanDinasSearch extends PengajuanDinas
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pengajuan_dinas', 'id_karyawan', 'disetujui_oleh'], 'integer'],
            [['keterangan_perjalanan', 'tanggal', 'disetujui_pada'], 'safe'],
            [['estimasi_biaya', 'biaya_yang_disetujui'], 'number'],
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
        // dd($tgl_mulai);
        $query = PengajuanDinas::find()->where(['>=', 'tanggal_mulai', $tgl_mulai])->andWhere(['<=', 'tanggal_selesai', $tgl_selesai]);

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
            'id_pengajuan_dinas' => $this->id_pengajuan_dinas,
            'id_karyawan' => $this->id_karyawan,
            // 'tanggal_mulai' => $this->tanggal_mulai,
            // 'tanggal_selesai' => $this->tanggal_selesai,
            'estimasi_biaya' => $this->estimasi_biaya,
            'biaya_yang_disetujui' => $this->biaya_yang_disetujui,
            'disetujui_oleh' => $this->disetujui_oleh,
            'disetujui_pada' => $this->disetujui_pada,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'keterangan_perjalanan', $this->keterangan_perjalanan]);

        return $dataProvider;
    }
}
