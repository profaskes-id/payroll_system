<?php

namespace backend\controllers;

use backend\models\PotonganDetailSearch;
use backend\models\TotalSearch;
use backend\models\TunjanganDetailSearch;
use yii\data\ActiveDataProvider;

class TunjanganPotonganController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $searchModel = new TotalSearch();

        // Get data untuk tunjangan
        $tunjanganQuery = $searchModel->searchTunjangan($this->request->queryParams);
        $dataTunjanganProvider = new ActiveDataProvider([
            'query' => $tunjanganQuery,
            'pagination' => [
                'pageSize' => 30,
            ],
            'sort' => [
                'defaultOrder' => ['id_karyawan' => SORT_DESC],
                'attributes' => [
                    'id_karyawan',
                    'nama',
                    'total_tunjangan',
                    'nama_bagian',
                    'nama_kode'
                ]
            ],
        ]);

        // Get data untuk potongan
        $potonganQuery = $searchModel->searchPotongan($this->request->queryParams);
        $dataPotonganProvider = new ActiveDataProvider([
            'query' => $potonganQuery,
            'pagination' => [
                'pageSize' => 30,
            ],
            'sort' => [
                'defaultOrder' => ['id_karyawan' => SORT_DESC],
                'attributes' => [
                    'id_karyawan',
                    'nama',
                    'total_potongan',
                    'nama_bagian',
                    'nama_kode'
                ]
            ],
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel, // Hanya satu model search
            'dataTunjanganProvider' => $dataTunjanganProvider,
            'dataPotonganProvider' => $dataPotonganProvider,
        ]);
    }
}
