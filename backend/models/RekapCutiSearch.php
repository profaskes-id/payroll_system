<?php

namespace backend\models;

use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\db\Query;
use yii\base\Model;
use backend\models\RekapCuti;
use Yii;

/**
 * RekapCutiSearch represents the model behind the search form of `backend\models\RekapCuti`.
 */
class RekapCutiSearch extends RekapCuti
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_rekap_cuti', 'id_master_cuti', 'id_karyawan', 'total_hari_terpakai'], 'integer'],
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

    // public function search($params)
    // {

    //     $jenis_cuti = 1;
    //     $tahun = date('Y');

    //     $query = (new Query())
    //         ->select([
    //             'karyawan.id_karyawan',
    //             'karyawan.nama',
    //             'karyawan.kode_jenis_kelamin',
    //             'rekap_cuti.id_rekap_cuti',
    //             'rekap_cuti.id_master_cuti',
    //             'rekap_cuti.total_hari_terpakai',
    //             'rekap_cuti.tahun',
    //             'master_cuti.jenis_cuti',
    //             'master_cuti.total_hari_pertahun',
    //         ])
    //         ->from('karyawan')
    //         ->leftJoin(
    //             'rekap_cuti',
    //             "rekap_cuti.id_karyawan = karyawan.id_karyawan 
    //             AND rekap_cuti.id_master_cuti = $jenis_cuti AND rekap_cuti.tahun = $tahun"
    //         )
    //         ->leftJoin('master_cuti', 'master_cuti.id_master_cuti = rekap_cuti.id_master_cuti ')
    //         ->where('karyawan.is_aktif = 1')
    //         ->orderBy(['karyawan.nama' => SORT_ASC]);


    //     $dataProvider = new ActiveDataProvider([
    //         'query' => $query,
    //     ]);

    //     $this->load($params);

    //     if (!$this->validate()) {
    //         return $dataProvider;
    //     }


    //     return $dataProvider;
    // }


    public function search($params)
    {
        $this->load($params);

        // Ambil nilai dari params jika ada, kalau tidak set default 1
        $jenis_cuti = $this->id_master_cuti ?: 1;
        $tahun = $this->tahun ?: date('Y');

        // $rekapId18Karyawan = RekapCuti::find()
        //     ->where(['id_karyawan' => 18, 'tahun' => $tahun, 'id_master_cuti' => $jenis_cuti])
        //     ->all();

        // dd($rekapId18Karyawan);


        $query = (new \yii\db\Query())
            ->select([
                'karyawan.id_karyawan',
                'karyawan.nama',
                'karyawan.kode_jenis_kelamin',
                // Kalau kamu perlu id_rekap_cuti, biasanya harus dihapus karena GROUP BY
                'COALESCE(SUM(rekap_cuti.total_hari_terpakai), 0) AS total_hari_terpakai',
                'COALESCE(rekap_cuti.tahun, :tahun) AS tahun',
                'master_cuti.jenis_cuti',
                'master_cuti.total_hari_pertahun',
                'COALESCE(rekap_cuti.id_master_cuti, 0) AS id_master_cuti',
            ])
            ->from('karyawan')
            ->leftJoin(
                'rekap_cuti',
                "rekap_cuti.id_karyawan = karyawan.id_karyawan
         AND rekap_cuti.tahun = :tahun
         AND (:id_master_cuti = 0 OR rekap_cuti.id_master_cuti = :id_master_cuti)",
                [
                    ':tahun' => $tahun,
                    ':id_master_cuti' => $jenis_cuti
                ]
            )
            ->leftJoin('master_cuti', 'master_cuti.id_master_cuti = rekap_cuti.id_master_cuti')
            ->where(['karyawan.is_aktif' => 1,])
            ->groupBy([
                'karyawan.id_karyawan',
                'karyawan.nama',
                'karyawan.kode_jenis_kelamin',
                'rekap_cuti.tahun',
                'master_cuti.jenis_cuti',
                'master_cuti.total_hari_pertahun',
                'rekap_cuti.id_master_cuti',
            ])
            ->orderBy(['karyawan.nama' => SORT_ASC]);
        if (!empty($this->id_karyawan)) {
            $query->andWhere(['karyawan.id_karyawan' => $this->id_karyawan]);
        }

        if ($jenis_cuti == 2) {
            $query->andWhere(['karyawan.kode_jenis_kelamin' => 'P']);
        }

        // Simpan untuk ke form dan view
        $this->id_master_cuti = $jenis_cuti;
        $this->tahun = $tahun;

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 20],
        ]);
    }
}
