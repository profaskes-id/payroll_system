<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PengajuanKasbon;

/**
 * PengajuanKasbonSearch represents the model behind the search form of `backend\models\PengajuanKasbon`.
 */
class PengajuanKasbonSearch extends PengajuanKasbon
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pengajuan_kasbon', 'id_karyawan', 'lama_cicilan', 'disetujui_oleh', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['gaji_pokok', 'jumlah_kasbon', 'angsuran_perbulan'], 'number'],
            [['tanggal_pengajuan', 'tanggal_pencairan', 'tanggal_mulai_potong', 'keterangan', 'tanggal_disetujui'], 'safe'],
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
        $query = PengajuanKasbon::find();

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
            'id_pengajuan_kasbon' => $this->id_pengajuan_kasbon,
            'id_karyawan' => $this->id_karyawan,
            'gaji_pokok' => $this->gaji_pokok,
            'jumlah_kasbon' => $this->jumlah_kasbon,
            'tanggal_pengajuan' => $this->tanggal_pengajuan,
            'tanggal_pencairan' => $this->tanggal_pencairan,
            'lama_cicilan' => $this->lama_cicilan,
            'angsuran_perbulan' => $this->angsuran_perbulan,
            'tanggal_mulai_potong' => $this->tanggal_mulai_potong,
            'tanggal_disetujui' => $this->tanggal_disetujui,
            'disetujui_oleh' => $this->disetujui_oleh,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'keterangan', $this->keterangan]);

        return $dataProvider;
    }
}
