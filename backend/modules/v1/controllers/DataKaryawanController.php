<?php

namespace app\modules\v1\controllers;

use backend\models\Karyawan as KaryawanModel;
use yii\rest\ActiveController;

class DataKaryawanController extends ActiveController
{
    public $modelClass = KaryawanModel::class;


    public function actionPribadi($id_karyawan)
    {


        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $karyawan = $this->modelClass::find()
            ->where(['id_karyawan' => $id_karyawan])
            ->select([
                'karyawan.*',
                'ji.nama_kode AS jenis_identitas',
                'jk.nama_kode AS kode_jenis_kelamin',
                'sn.nama_kode AS status_nikah',
                'ag.nama_kode AS agama',
                // Identitas
                'mp.nama_prop AS kode_provinsi_identitas',
                'mt.nama_kab AS kode_kabupaten_kota_identitas',
                'mk.nama_kec AS kode_kecamatan_identitas',
                // Domisili
                'mpd.nama_prop AS kode_provinsi_domisili',
                'mtd.nama_kab AS kode_kabupaten_kota_domisili',
                'mkd.nama_kec AS kode_kecamatan_domisili',
            ])
            ->leftJoin('master_kode AS ji', 'ji.nama_group = "jenis-identitas" AND ji.kode = karyawan.jenis_identitas')
            ->leftJoin('master_kode AS jk', 'jk.nama_group = "jenis-kelamin" AND jk.kode = karyawan.kode_jenis_kelamin')
            ->leftJoin('master_kode AS sn', 'sn.nama_group = "status-pernikahan" AND sn.kode = karyawan.status_nikah')
            ->leftJoin('master_kode AS ag', 'ag.nama_group = "agama" AND ag.kode = karyawan.agama')
            // Join untuk identitas
            ->leftJoin('master_prop AS mp', 'mp.kode_prop = karyawan.kode_provinsi_identitas')
            ->leftJoin('master_kab AS mt', 'mt.kode_kab = karyawan.kode_kabupaten_kota_identitas')
            ->leftJoin('master_kec AS mk', 'mk.kode_kec = karyawan.kode_kecamatan_identitas')
            // Join untuk domisili
            ->leftJoin('master_prop AS mpd', 'mpd.kode_prop = karyawan.kode_provinsi_domisili')
            ->leftJoin('master_kab AS mtd', 'mtd.kode_kab = karyawan.kode_kabupaten_kota_domisili')
            ->leftJoin('master_kec AS mkd', 'mkd.kode_kec = karyawan.kode_kecamatan_domisili')
            ->asArray()
            ->one();

        return $karyawan;
    }
}
