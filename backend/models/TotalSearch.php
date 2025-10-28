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
            ->select(['id_karyawan', 'SUM(jumlah) as total_tunjangan'])
            ->groupBy('id_karyawan');

        $query = Karyawan::find()
            ->select([
                'karyawan.id_karyawan',
                'karyawan.nama',
                'COALESCE(t.total_tunjangan, 0) as total_tunjangan',
                'bagian.nama_bagian',
                'master_kode.nama_kode'
            ])
            ->where(['karyawan.is_aktif' => 1])
            ->leftJoin(['t' => $subQuery], 't.id_karyawan = karyawan.id_karyawan')
            ->leftJoin('data_pekerjaan', 'data_pekerjaan.id_karyawan = karyawan.id_karyawan AND data_pekerjaan.is_aktif = 1')
            ->leftJoin('bagian', 'bagian.id_bagian = data_pekerjaan.id_bagian')
            ->leftJoin('master_kode', 'data_pekerjaan.jabatan = master_kode.kode AND master_kode.nama_group = "jabatan"')
            ->asArray();

        if (!$this->validate()) {
            return $query;
        }

        // Filter conditions
        if ($this->id_karyawan) {
            $query->andWhere(['karyawan.id_karyawan' => $this->id_karyawan]);
        }

        return $query;
    }

    public function searchPotongan($params)
    {
        $this->load($params);

        $subQuery = PotonganDetail::find()
            ->select(['id_karyawan', 'SUM(jumlah) as total_potongan'])
            ->groupBy('id_karyawan');

        $query = Karyawan::find()
            ->select([
                'karyawan.id_karyawan',
                'karyawan.nama',
                'COALESCE(p.total_potongan, 0) as total_potongan',
                'bagian.nama_bagian',
                'master_kode.nama_kode'
            ])
            ->where(['karyawan.is_aktif' => 1])
            ->leftJoin(['p' => $subQuery], 'p.id_karyawan = karyawan.id_karyawan')
            ->leftJoin('data_pekerjaan', 'data_pekerjaan.id_karyawan = karyawan.id_karyawan AND data_pekerjaan.is_aktif = 1')
            ->leftJoin('bagian', 'bagian.id_bagian = data_pekerjaan.id_bagian')
            ->leftJoin('master_kode', 'data_pekerjaan.jabatan = master_kode.kode AND master_kode.nama_group = "jabatan"')
            ->asArray();

        if (!$this->validate()) {
            return $query;
        }

        // Filter conditions
        if ($this->id_karyawan) {
            $query->andWhere(['karyawan.id_karyawan' => $this->id_karyawan]);
        }

        return $query;
    }
}
