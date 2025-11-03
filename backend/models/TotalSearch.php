<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class TotalSearch extends Model
{
    public $id_karyawan;

    public function rules()
    {
        return [
            [['id_karyawan'], 'integer'],
        ];
    }

    public function searchTunjangan($params)
    {
        $this->load($params);

        $subQuery = TunjanganDetail::find()
            ->select([
                'tunjangan_detail.id_karyawan',
                new \yii\db\Expression("
                SUM(
                    CASE 
                        WHEN tunjangan_detail.jumlah > 100 THEN tunjangan_detail.jumlah
                        ELSE (tunjangan_detail.jumlah / 100) * COALESCE(master_gaji.nominal_gaji, 0)
                    END
                ) AS total_tunjangan
            ")
            ])
            ->leftJoin('master_gaji', 'master_gaji.id_karyawan = tunjangan_detail.id_karyawan')
            ->where(['tunjangan_detail.status' => 1])
            ->groupBy('tunjangan_detail.id_karyawan');

        $query = Karyawan::find()
            ->select([
                'karyawan.id_karyawan',
                'karyawan.nama',
                'COALESCE(t.total_tunjangan, 0) as total_tunjangan',
                'bagian.nama_bagian',
                'master_kode.nama_kode',
                'COALESCE(master_gaji.nominal_gaji, 0) as nominal_gaji'
            ])
            ->where(['karyawan.is_aktif' => 1])
            ->leftJoin(['t' => $subQuery], 't.id_karyawan = karyawan.id_karyawan')
            ->leftJoin('data_pekerjaan', 'data_pekerjaan.id_karyawan = karyawan.id_karyawan AND data_pekerjaan.is_aktif = 1')
            ->leftJoin('bagian', 'bagian.id_bagian = data_pekerjaan.id_bagian')
            ->leftJoin('master_kode', 'data_pekerjaan.jabatan = master_kode.kode AND master_kode.nama_group = "jabatan"')
            ->leftJoin('master_gaji', 'master_gaji.id_karyawan = karyawan.id_karyawan')
            ->asArray();

        if (!$this->validate()) {
            return $query;
        }

        if ($this->id_karyawan) {
            $query->andWhere(['karyawan.id_karyawan' => $this->id_karyawan]);
        }

        return $query;
    }


    public function searchPotongan($params)
    {
        $this->load($params);

        // Subquery untuk menghitung total potongan per karyawan
        $subQuery = PotonganDetail::find()
            ->select([
                'potongan_detail.id_karyawan',
                new \yii\db\Expression("
                SUM(
                    CASE 
                        WHEN potongan_detail.jumlah > 100 THEN potongan_detail.jumlah
                        ELSE (potongan_detail.jumlah / 100) * COALESCE(master_gaji.nominal_gaji, 0)
                    END
                ) AS total_potongan
            ")
            ])
            ->leftJoin('master_gaji', 'master_gaji.id_karyawan = potongan_detail.id_karyawan')
            ->where(['potongan_detail.status' => 1])
            ->groupBy('potongan_detail.id_karyawan');

        // Query utama ke tabel karyawan
        $query = Karyawan::find()
            ->select([
                'karyawan.id_karyawan',
                'karyawan.nama',
                'COALESCE(p.total_potongan, 0) AS total_potongan',
                'bagian.nama_bagian',
                'master_kode.nama_kode',
                'COALESCE(master_gaji.nominal_gaji, 0) AS nominal_gaji'
            ])
            ->where(['karyawan.is_aktif' => 1])
            ->leftJoin(['p' => $subQuery], 'p.id_karyawan = karyawan.id_karyawan')
            ->leftJoin('data_pekerjaan', 'data_pekerjaan.id_karyawan = karyawan.id_karyawan AND data_pekerjaan.is_aktif = 1')
            ->leftJoin('bagian', 'bagian.id_bagian = data_pekerjaan.id_bagian')
            ->leftJoin('master_kode', 'data_pekerjaan.jabatan = master_kode.kode AND master_kode.nama_group = "jabatan"')
            ->leftJoin('master_gaji', 'master_gaji.id_karyawan = karyawan.id_karyawan')
            ->asArray();

        if (!$this->validate()) {
            return $query;
        }

        // Filter kondisi tambahan
        if ($this->id_karyawan) {
            $query->andWhere(['karyawan.id_karyawan' => $this->id_karyawan]);
        }

        // Debug / hasil


        return $query;
    }
}
