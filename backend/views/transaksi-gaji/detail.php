<?php

use backend\models\helpers\JamKerjaHelper;
use backend\models\helpers\PeriodeGajiHelper;
use backend\models\Tanggal;
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
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
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
                            [
                                'label' => ucwords(str_replace('_', ' ', 'jumlah_hadir')),
                                'value' => $model->jumlah_hadir . ' Hari',
                            ],
                            [
                                'label' => ucwords(str_replace('_', ' ', 'jumlah_sakit')),
                                'value' => $model->jumlah_sakit . ' Hari',
                            ],
                            [
                                'label' => ucwords(str_replace('_', ' ', 'jumlah_wfh')),
                                'value' => $model->jumlah_wfh . ' Hari',
                            ],
                            [
                                'label' => ucwords(str_replace('_', ' ', 'jumlah_cuti')),
                                'value' => $model->jumlah_cuti . ' Hari',
                            ],
                            [
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
                                'attribute' => 'jumlah_jam_lembur',
                                'label' => 'Durasi Lembur',
                                'value' => function ($model) {
                                    return $model->jumlah_jam_lembur . ' Jam';
                                },
                                'format' => 'raw',
                            ],
                        ],
                    ]
                ) ?>
            </div>
            <div class="col-6">
                <?= DetailView::widget([
                    'model' => $model,
                    'template' => '<tr><th>{label}</th><td>{value}</td></tr>',
                    'attributes' => [
                        [
                            'attribute' =>  'lembur_perjam',
                            'value' => function ($model) {
                                return 'Rp ' . number_format($model->lembur_perjam, 0, ',', '.');
                            }
                        ],

                        [
                            'attribute' =>  'total_lembur',
                            'value' => function ($model) {
                                return 'Rp ' .  number_format($model->total_lembur, 0, ',', '.');
                            }
                        ],
                        [
                            'attribute' =>  'gaji_pokok',
                            'value' => function ($model) {
                                return 'Rp ' . number_format($model->gaji_pokok, 0, ',', '.');
                            }
                        ],
                        [
                            'attribute' =>  'jumlah_tunjangan',
                            'value' => function ($model) {
                                return 'Rp ' . number_format($model->jumlah_tunjangan, 0, ',', '.');
                            }
                        ],
                        [
                            'attribute' =>  'jumlah_potongan',
                            'value' => function ($model) {
                                return 'Rp ' . number_format($model->jumlah_potongan, 0, ',', '.');
                            }
                        ],
                        [
                            'attribute' => 'potongan_wfh_hari',
                            'value' => function ($model) {
                                return 'Rp ' . number_format($model->potongan_wfh_hari, 0, ',', '.');
                            }
                        ],
                        [
                            'attribute' => 'jumlah_potongan_wfh',
                            'value' => function ($model) {
                                return 'Rp ' . number_format($model->jumlah_potongan_wfh, 0, ',', '.');
                            }
                        ],
                        [
                            'attribute' => 'gaji_diterima',
                            'value' => function ($model) {
                                return 'Rp ' . number_format($model->gaji_diterima, 0, ',', '.');
                            }
                        ],
                    ],
                ]) ?>
            </div>

        </div>


    </div>
</div>