<?php

use backend\models\DataPekerjaan;
use backend\models\Karyawan;
use backend\models\Tanggal;
use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\KaryawanSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Karyawan';
$this->params['breadcrumbs'][] = $this->title;
$tanggalFormater = new Tanggal();
?>
<div class="karyawan-index">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-regular fa-plus"></i> Add New', ['create'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <button style="width: 100%;" class="add-button" type="submit" data-toggle="collapse" data-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample">
        <i class="fas fa-search"></i>
        <span>
            Search
        </span>
    </button>
    <div style="margin-top: 10px;">
        <div class="collapse width" id="collapseWidthExample">
            <div class="" style="width: 100%;">
                <?php echo $this->render('_search', ['model' => $searchModel]); ?>
            </div>
        </div>
    </div>


    <div class="table-container table-responsive">


        <div class="justify-end d-flex ">
            <p class="d-block">
                <?= Html::a(
                    'Export to exel <i class="fa fa-table"></i>',
                    array_merge(['exel']),
                    ['target' => '_blank', 'class' => 'tambah-button']
                ) ?>
            </p>
        </div>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'options' => ['class' => 'table table-striped table-responsive table-hover'],
            'columns' => [
                [
                    'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'contentOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'class' => 'yii\grid\SerialColumn'
                ],
                [
                    'class' => ActionColumn::className(),
                    'header' => Html::img(Yii::getAlias('@root') . '/images/icons/grid.svg', ['alt' => 'grid']),
                    'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'urlCreator' => function ($action, $model, $key, $index, $column) {


                        return Url::toRoute([$action, 'id_karyawan' => $model['id_karyawan']]);
                    }
                ],

                [

                    'label' => 'Nama',
                    'attribute' => 'nama',
                    'value' => function ($model) {
                        return $model['nama'];
                    }
                ],
                [
                    'headerOptions' => ['style' => 'width: 10%; text-align: center;'],
                    'contentOptions' => ['style' => 'width: 10%; text-align: center;'],
                    'label' => 'KODE',
                    'attribute' => 'kode_karyawan',
                    'value' => 'kode_karyawan',
                ],
                [
                    'attribute' => 'kode_jenis_kelamin',
                    'label' => 'Jenis Kelamin',
                    'value' => function ($model) {

                        return $model['kode_jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan';
                    }
                ],
                [
                    'attribute' => 'bagian',
                    'label' => 'Bagian',
                    'value' => function ($model) {
                        $divisiAktif = [];

                        $filteredData = array_filter($model->dataPekerjaans, function ($item) {
                            return $item->is_aktif == 1;
                        });
                        foreach ($filteredData as $key => $value) {
                            $divisiAktif[] = $value->bagian->nama_bagian;
                        }

                        return implode(', ', $divisiAktif);
                    }
                ],
                [

                    'label' => 'Status',
                    'format' => 'raw',
                    'contentOptions' => ['style' => 'width: 10%; text-align: center;'],
                    'value' => function ($model) use ($tanggalFormater, $statusKontrak) {
                        if (!$model->is_aktif == 1) {
                            return '<span class=""></span>';
                        }
                        if (empty($model->dataPekerjaans)) {
                            return "";
                        }


                        $filteredDataPekerjaans = $model->dataPekerjaans;
                        $pekerjaanAktif = null;
                        foreach ($filteredDataPekerjaans as $pekerjaan) {
                            if ($pekerjaan->is_aktif == 1) {
                                $pekerjaanAktif = $pekerjaan;
                                break;
                            }
                        }

                        // Jika tidak ada data aktif
                        if (!$pekerjaanAktif) {
                            return "Tidak ada pekerjaan aktif.";
                        }

                        // Ambil status dari pekerjaan aktif
                        $statusAktif = $pekerjaanAktif->status;
                        // Hitung berapa kali status ini muncul
                        $jumlah = 0;
                        foreach ($filteredDataPekerjaans as $pekerjaan) {

                            if ($pekerjaan->status == $statusAktif) {
                                $jumlah++;
                            }
                        }


                        $statusAktif = $pekerjaanAktif->statusPekerjaan->nama_kode;

                        // Kembalikan dalam format yang diminta
                        return  $jumlah > 1 ? "$statusAktif ($jumlah)" : "$statusAktif";
                    },

                ],
                [
                    'attribute' => 'tanggal_masuk',
                    'label' => 'Tanggal Masuk',
                    'format' => 'raw',
                    'value' => function ($model) use ($tanggalFormater, $statusKontrak) {
                        if (!$model->is_aktif == 1) {
                            return '<span class=""></span>';
                        }
                        if (empty($model->dataPekerjaans)) {
                            return "";
                        }
                        $filteredDataPekerjaans = array_filter($model->dataPekerjaans, function ($dataPekerjaan) use ($statusKontrak) {
                            return $dataPekerjaan->status == $statusKontrak;
                        });
                        $filteredDataPekerjaans = array_values($filteredDataPekerjaans);
                        if (empty($filteredDataPekerjaans)) {
                            return "";
                        }
                        $dates = array_map(function ($dataPekerjaan) {
                            return $dataPekerjaan->dari;  // 'dari' is the field with the date
                        }, $filteredDataPekerjaans);

                        $earliestDate = max($dates);
                        return $tanggalFormater->getIndonesiaFormatTanggal(Yii::$app->formatter->asDate($earliestDate, 'php:Y-m-d'));
                    },

                ],
                [
                    'label' => 'Masa Kerja',
                    'attribute' => 'masa_kerja',
                    'headerOptions' => ['style' => 'width: 10%; text-align: center;'],
                    'value' => function ($model) {
                        $statusdataPekerjaan = DataPekerjaan::findAll(['id_karyawan' => $model['id_karyawan'], 'is_aktif' => 1]);
                        if ($statusdataPekerjaan == null) {
                            return "";
                        }
                        $dataPekerjaan = DataPekerjaan::findAll(['status' => $statusdataPekerjaan, 'id_karyawan' => $model['id_karyawan']]);


                        $dates = array_map(function ($dataPekerjaan) {
                            return $dataPekerjaan->dari;
                        }, $dataPekerjaan);





                        $earliestDate = min($dates);


                        $startDate = new DateTime($earliestDate);
                        $endDate = new DateTime(); // Tanggal hari ini

                        $interval = $startDate->diff($endDate);

                        $years = $interval->y;
                        $months = $interval->m;
                        $days = $interval->d;

                        // Format durasi masa kerja
                        $duration = '';
                        if ($years > 0) {
                            $duration .= $years . ' tahun ';
                        }
                        if ($months > 0) {
                            $duration .= $months . ' bulan ';
                        }
                        if ($days > 0) {
                            $duration .= $days . ' hari';
                        }

                        return $duration ?: 'Kurang dari 1 hari';
                    }
                ],


                [

                    'header' => 'Aktif',
                    'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'contentOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'value' => function ($model) {

                        return $model->is_aktif == 1 ? '<span class="text-success">Aktif</span>' : '<span class="text-danger">Resign</span>';
                    },
                    'format' => 'raw',
                ],
                [
                    'header' => 'User Aktif',
                    'headerOptions' => ['style' => 'width: 10%; text-align: center;'],
                    'contentOptions' => ['style' => 'width: 10%; text-align: center;'],
                    'value' => function ($model) {
                        if (!$model->is_aktif == 1) {
                            return '<span class="text-danger">Resign</span>';
                        }
                        if (!$model->is_invite) {
                            return Html::a('<i class="fas fa-user-plus"></i>', ['invite', 'id_karyawan' => $model->id_karyawan], [
                                'title' => 'Invite',
                                'data-pjax' => '0',
                            ]);
                        } else {
                            return "<p class='text-success'>Aktif</p>";
                        }
                    },
                    'format' => 'raw',
                ],

            ],
        ]); ?>
    </div>


</div>