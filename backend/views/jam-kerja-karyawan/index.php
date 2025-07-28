<?php

use backend\models\DataPekerjaan;
use backend\models\JamKerjaKaryawan;
use backend\models\Karyawan;
use backend\models\MasterKode;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\JamKerjaKaryawanSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Jam Kerja Karyawan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jam-kerja-karyawan-index">


    <button style="width: 100%;" class="add-button" type="submit" data-toggle="collapse" data-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample">
        <i class="fas fa-search"></i>
        <span>
            Search
        </span>
    </button>
    <div style="margin-top: 10px;">
        <div class="collapse width" id="collapseWidthExample">
            <div class="" style="width: 100%;">
                <?= $this->render('_search', ['model' => $searchModel]);
                ?>
            </div>
        </div>
    </div>

    <div class="table-container table-responsive">

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'summary' => false,
            'columns' => [
                [
                    'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'contentOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'class' => 'yii\grid\SerialColumn'
                ],
                   [
                    'header' => Html::img(Yii::getAlias('@root') . '/images/icons/grid.svg', ['alt' => 'grid']),
                    'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                       'format' => 'raw',
                    'value' => function ($model) {

                        if ($model['id_jam_kerja']) {
                            return Html::a('<button  style=" border-radius: 6px !important; background: #488aec50 !important; color:#252525; all:unset;  display: block;">
                                        <span style="margin: 3px 3px !important;display: block; background: #488aec !important; padding: 0px 3px 0.1px !important; border-radius: 6px !important;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24"><path fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.5 3.75H6A2.25 2.25 0 0 0 3.75 6v1.5M16.5 3.75H18A2.25 2.25 0 0 1 20.25 6v1.5m0 9V18A2.25 2.25 0 0 1 18 20.25h-1.5m-9 0H6A2.25 2.25 0 0 1 3.75 18v-1.5M15 12a3 3 0 1 1-6 0a3 3 0 0 1 6 0"/></svg>
                                        </span>
                                        </button>', ['view', 'id_karyawan' => $model['id_karyawan']]);
                        } else {
                            return Html::a('<button  style=" border-radius: 6px !important; background: #E9EC4850 !important; color:#252525; all:unset;  display: block;">
                                        <span style="margin: 3px 3px !important;display: block; background: #E9EC48 !important; padding: 0px 3px 0.1px !important; border-radius: 6px !important;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24"><path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844l2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565l6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/></svg>
                                         </span>
                                        </button>', ['create', 'id_karyawan' => $model['id_karyawan'], ],);
                        }
                    }
                ],
                [
                    'attribute' => 'Karyawan',
                    'value' => function ($model) {
                        return $model['nama'];
                    }
                ],
                [
                    'attribute' => 'Jabatan',
                    'label' => 'Jabatan',
                    'value' => function ($model) {
                        // Pastikan model memiliki id_karyawan dan jabatan
                        if (!isset($model['id_karyawan']) || !isset($model['jabatan'])) {
                            return '-';
                        }

                        $dataPekerjaan = DataPekerjaan::find()
                            ->where([
                                'id_karyawan' => $model['id_karyawan'],
                                'jabatan' => $model['jabatan'],
                                'is_aktif' => 1
                            ])
                            ->one();

                        // Cek jika data pekerjaan dan relasinya ada
                        if ($dataPekerjaan && $dataPekerjaan->jabatanPekerja) {
                            return $dataPekerjaan->jabatanPekerja->nama_kode ?? '-';
                        }

                        return '-';
                    },
                ],
                [
                    'attribute' => 'Jam Kerja',
                    'value' => function ($model) {

                        if (!$model['nama_jam_kerja']) {
                            return '<p class="text-danger">(Belum Diset)</p>';
                        }
                        return "{$model['nama_jam_kerja']} ({$model['nama_kode']})" ?? '<p class="text-danger">(Belum Diset)</p>';
                    },
                    'format' => 'raw'
                ],
                [
                    'format' => 'raw',
                    'attribute' => 'Jenis Shift',
                    'value' => function ($model) {
                        if ($model['is_shift']) {
                            if ($model['is_shift'] == 1) {
                                return 'Shift';
                            } else {
                                return 'Non Shift';
                            }
                        }
                        return '<p class="text-danger">(Belum Diset)</p>';
                    }
                ],

                [
                    'attribute' => 'Maximal Terlambat',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model['max_terlambat'] != null) {
                            return date('H:i', strtotime($model['max_terlambat']));
                        }
                        return '<p class="text-danger">(Belum Diset)</p>';
                    }
                ],
            ],
        ]); ?>
    </div>


</div>