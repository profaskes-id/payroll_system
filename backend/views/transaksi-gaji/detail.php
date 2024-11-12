<?php

use backend\models\helpers\JamKerjaHelper;
use backend\models\helpers\PeriodeGajiHelper;
use backend\models\Tanggal;
use backend\models\Terbilang;
use backend\models\TransaksiGaji;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\TransaksiGaji $model */

$periode = PeriodeGajiHelper::getPeriodeGaji($model['periode_gaji']);
$tanggal = new Tanggal();

$periode_return = $tanggal->getBulan($periode['bulan']) . ' ' . $periode['tahun'];
$this->title = 'Transaksi Gaji ' .  $model['nama'] . ' (' . $periode_return . ')';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Transaksi Gaji'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="transaksi-gaji-view">
    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>



    <div class="table-container table-responsive">
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('update', ['update', 'id_transaksi_gaji' => $model->id_transaksi_gaji, 'id_karyawan' => $id_karyawan['id_karyawan'],], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_transaksi_gaji' => $model->id_transaksi_gaji], [
                'class' => 'reset-button',
                'data' => [
                    'confirm' => 'Apakah Anda Yakin Ingin Menghapus Item Ini ?',
                    'method' => 'post',
                ],
            ]) ?>
            <?= Html::a('Cetak', ['cetak', 'id_transaksi_gaji' => $model->id_transaksi_gaji, 'id_karyawan' => $id_karyawan['id_karyawan']], ['class' => 'tambah-button', 'target' => '_blank']) ?>

        </p>

        <div class="row">
            <div class="col-6">
                <?= DetailView::widget(
                    [
                        'model' => $model,
                        'template' => '<tr><th>{label}</th><td>{value}</td></tr>',
                        'attributes' => [
                            'nomer_identitas',
                            'kode_karyawan',
                            'nama',
                            'bagian',
                            'jabatan',
                            [
                                'label' => "Jam Kerja",
                                'value' => function ($model) {
                                    $data = JamKerjaHelper::getJamKerja($model['jam_kerja']);
                                    return   $data['nama_jam_kerja'];
                                }
                            ],
                            [
                                'label' => "Periode Gaji",
                                'format' => 'raw',
                                'value' => function ($model) {
                                    $data =  PeriodeGajiHelper::getPeriodeGaji($model['periode_gaji']);
                                    $tanggal = new Tanggal();
                                    $result = "<div class> 
                                    <p class='m-0'>{$tanggal->getBulan($data['bulan'])} {$data['tahun']} </p>
                                    <span class='text-xs'>({$tanggal->getIndonesiaFormatTanggal($data['tanggal_awal'])} - {$tanggal->getIndonesiaFormatTanggal($data['tanggal_akhir'])})    
                                    </div>";

                                    return $result;
                                }
                            ],
                            [
                                'label' => ucwords(str_replace('_', ' ', 'jumlah_hari_kerja')),
                                'value' => $model->jumlah_hari_kerja . ' Hari',
                            ],

                        ],
                    ]
                ) ?>
                <?= DetailView::widget(
                    [
                        'model' => $model,
                        'attributes' => [
                            [
                                'contentOptions' => ['style' => 'width: 30%'],
                                'label' => ucwords(str_replace('_', ' ', 'jumlah_hadir')),
                                'value' => $model->jumlah_hadir . ' Hari',
                            ],
                            [
                                'contentOptions' => ['style' => 'width: 30%'],
                                'label' => ucwords(str_replace('_', ' ', 'jumlah_sakit')),
                                'value' => $model->jumlah_sakit . ' Hari',
                            ],
                            [
                                'contentOptions' => ['style' => 'width: 30%'],
                                'label' => ucwords(str_replace('_', ' ', 'jumlah_wfh')),
                                'value' => $model->jumlah_wfh . ' Hari',
                            ],
                            [
                                'contentOptions' => ['style' => 'width: 30%'],
                                'label' => ucwords(str_replace('_', ' ', 'jumlah_cuti')),
                                'value' => $model->jumlah_cuti . ' Hari',
                            ],




                        ],
                    ]
                ) ?>
            </div>
            <div class="col-6">

                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        [

                            'contentOptions' => ['style' => 'width: 30%'],
                            'label' => "Tidak Hadir",
                            'value' => function ($model) {
                                $dataTotalHariKerja = $model['jumlah_hari_kerja'];
                                $dataTotalHadir = $model['jumlah_hadir'] ?? 0;
                                $dataSakit =  $model['jumlah_sakit'] ?? 0;
                                $dataCuti = $model['jumlah_cuti'] ?? 0;
                                return   $dataTotalHariKerja - $dataTotalHadir - $dataSakit - $dataCuti . ' Hari';
                            }
                        ],
                        [
                            'contentOptions' => ['style' => 'width: 30%'],

                            'label' => 'Potongan Perhari',
                            'value' => function ($model) {
                                return 'Rp ' . number_format($model->potongan_tidak_hadir_hari, 0, ',', '.');
                            },
                            'format' => 'raw',
                        ],
                        [
                            'contentOptions' => ['style' => 'width: 30%'],

                            'label' => 'Total Potonganan',
                            'value' => function ($model) {;
                                return 'Rp ' . number_format($model->jumlah_potongan_tidak_hadir, 0, ',', '.');
                            },
                            'format' => 'raw',
                        ],
                    ]
                ]) ?>

                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        [
                            'contentOptions' => ['style' => 'width: 30%'],
                            'label' => 'Durasi Terlambat',
                            'value' => function ($model) {
                                return $model->jumlah_terlambat ?? "00:00:00";
                            },
                            'format' => 'raw',
                        ],
                        [

                            'contentOptions' => ['style' => 'width: 30%'],
                            'label' => 'Potongan Terlambat Permenit',
                            'value' => function ($model) {
                                return 'Rp ' . number_format($model->potongan_terlambat_permenit, 0, ',', '.');
                            },
                            'format' => 'raw',
                        ],
                        [
                            'contentOptions' => ['style' => 'width: 30%'],
                            'label' => 'Total Potongan Terlambat',
                            'value' => function ($model) {
                                return 'Rp ' . number_format($model->jumlah_potongan_terlambat, 0, ',', '.');
                            },
                            'format' => 'raw',
                        ],
                    ]
                ]) ?>

                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        [
                            'contentOptions' => ['style' => 'width: 30%'],

                            'label' => 'Durasi Lembur',
                            'value' => function ($model) {
                                return $model->jumlah_jam_lembur;
                            },
                            'format' => 'raw',
                        ],
                        [
                            'contentOptions' => ['style' => 'width: 30%'],

                            'label' =>  'Bayawan Lembur Perjam',
                            'value' => function ($model) {
                                return 'Rp ' . number_format($model->lembur_perjam, 0, ',', '.');
                            }
                        ],

                        [
                            'contentOptions' => ['style' => 'width: 30%'],
                            'label' =>  'Total Bayaran Lembur',
                            'value' => function ($model) {
                                return 'Rp ' .  number_format($model->total_lembur, 0, ',', '.');
                            }
                        ],
                    ]
                ]) ?>

                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        [
                            'contentOptions' => ['style' => 'width: 30%'],

                            'label' => "Jumlah WFH",
                            'value' => $model->jumlah_wfh . ' Hari',
                        ],
                        [
                            'contentOptions' => ['style' => 'width: 30%'],
                            'label' => 'Potongan WFH Perhari',
                            'value' => function ($model) {
                                return 'Rp ' . number_format($model->potongan_wfh_hari, 0, ',', '.');
                            }
                        ],
                        [
                            'contentOptions' => ['style' => 'width: 30%'],
                            'label' => 'Total Potongan WFH',
                            'value' => function ($model) {
                                return 'Rp ' . number_format($model->jumlah_potongan_wfh, 0, ',', '.');
                            }
                        ],
                    ]
                ]) ?>


            </div>
            <div class="col-12">

                <hr>
            </div>
            <div class="col-12">
                <?= DetailView::widget([
                    'model' => $model,
                    'template' => '<tr><th>{label}</th><td>{value}</td></tr>',
                    'attributes' => [
                        [
                            'headerOptions' => ['style' => 'width: 30%'],

                            'attribute' => 'gaji_diterima',
                            'value' => function ($model) {
                                return 'Rp ' . number_format($model->gaji_diterima, 0, ',', '.');
                            }
                        ],
                        [
                            'headerOptions' => ['style' => 'width: 30%'],
                            'label' => 'Gaji Diterima (Terbilang)',
                            'value' => function ($model) {
                                $newTerbilang = new Terbilang();
                                $terbilang = $newTerbilang->toTerbilang($model->gaji_diterima);
                                return $terbilang . ' Rupiah';
                            }
                        ],
                    ],
                ]) ?></div>

        </div>


    </div>
</div>