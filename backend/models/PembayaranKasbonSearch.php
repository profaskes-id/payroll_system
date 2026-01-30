<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PembayaranKasbon;

/**
 * PembayaranKasbonSearch represents the model behind the search form of `backend\models\PembayaranKasbon`.
 */
class PembayaranKasbonSearch extends PembayaranKasbon
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pembayaran_kasbon', 'id_karyawan', 'id_kasbon', 'bulan', 'tahun', 'status_potongan', 'created_at',], 'integer'],
            [['jumlah_potong', 'sisa_kasbon'], 'number'],
            [['tanggal_potong'], 'safe'],
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
        // Subquery untuk mendapatkan created_at terakhir per karyawan
        $subQuery = PembayaranKasbon::find()
            ->select(['id_karyawan', 'MAX(created_at) as max_created'])
            ->groupBy('id_karyawan');

        $query = PembayaranKasbon::find()
            ->alias('pk')
            ->innerJoin(
                ['sq' => $subQuery],
                'pk.id_karyawan = sq.id_karyawan AND pk.created_at = sq.max_created'
            );
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'pk.id_pembayaran_kasbon' => $this->id_pembayaran_kasbon,
            'pk.id_karyawan' => $this->id_karyawan,
            'pk.id_kasbon' => $this->id_kasbon,
            'pk.bulan' => $this->bulan,
            'pk.tahun' => $this->tahun,
            'pk.jumlah_potong' => $this->jumlah_potong,
            'pk.tanggal_potong' => $this->tanggal_potong,
            'pk.status_potongan' => $this->status_potongan,
            'pk.sisa_kasbon' => $this->sisa_kasbon,
            'pk.created_at' => $this->created_at,
        ]);

        return $dataProvider;
    }
}
