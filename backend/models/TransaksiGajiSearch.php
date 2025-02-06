<?php

namespace backend\models;

use backend\models\helpers\PeriodeGajiHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\TransaksiGaji;
use PhpParser\Node\Stmt\Expression;
use Yii;
use yii\db\Query;
use yii\web\NotFoundHttpException;

/**
 * TransaksiGajiSearch represents the model behind the search form of `backend\models\TransaksiGaji`.
 */
class TransaksiGajiSearch extends TransaksiGaji
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_transaksi_gaji', 'jam_kerja', 'periode_gaji', 'jumlah_hari_kerja', 'jumlah_hadir', 'jumlah_sakit', 'jumlah_wfh', 'jumlah_cuti'], 'integer'],
            [['nomer_identitas', 'nama', 'bagian', 'jabatan', 'status_karyawan', 'jumlah_jam_lembur'], 'safe'],
            [['gaji_pokok', 'lembur_perjam', 'total_lembur', 'jumlah_tunjangan', 'jumlah_potongan', 'potongan_wfh_hari', 'jumlah_potongan_wfh', 'gaji_diterima'], 'number'],
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

    public function search($params, $bulan, $tahun, $id_karyawan, $periode_gaji_id)
    {


        if ($periode_gaji_id == null || $periode_gaji_id == '') {
            $dataPeriode = PeriodeGajiHelper::getPeriodeGajiBulanIni();
            if ($dataPeriode == null) {
                throw new NotFoundHttpException('Data Periode Gaji Tidak Di Ditemukan, silahkan Tambahkan Data Periode Gaji Di Menu Periode Gaji Terlebih Dahulu');
            }
            $periode_gaji_id = $dataPeriode['id_periode_gaji'];
        }
        $query = (new \yii\db\Query())
            ->select([
                'k.id_karyawan',
                'k.nomer_identitas',
                'pg.id_periode_gaji',
                'pg.bulan',
                'pg.tahun',
                'pg.terima',
                'tg.*',
            ])
            ->from('karyawan k')
            ->where(['k.is_aktif' => 1]);

        // Jika id_karyawan tidak null, tambahkan kondisi where
        $query->andWhere(['pg.id_periode_gaji' => $periode_gaji_id]);


        if ($id_karyawan != null) {
            $query->andWhere(['k.id_karyawan' => $id_karyawan]);
        }

        $query->leftJoin('periode_gaji pg', [
            'AND',
            'pg.bulan = :bulan',
            'pg.tahun = :tahun'
        ])
            ->leftJoin('transaksi_gaji tg', 'tg.periode_gaji = pg.id_periode_gaji AND tg.nomer_identitas = k.nomer_identitas')
            ->params([
                ':bulan' => $bulan,
                ':tahun' => $tahun
            ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }
}
