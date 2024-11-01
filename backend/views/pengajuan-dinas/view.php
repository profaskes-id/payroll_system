<?php

use amnah\yii2\user\models\User;
use backend\models\Tanggal;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanDinas $model */

$this->title = "Pengajuan Dinas " . $model->karyawan->nama;
$this->params['breadcrumbs'][] = ['label' => 'Pengajuan Dinas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pengajuan-dinas-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container table-responsive">
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Tanggapi', ['update', 'id_pengajuan_dinas' => $model->id_pengajuan_dinas], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_pengajuan_dinas' => $model->id_pengajuan_dinas], [
                'class' => 'reset-button',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'label' => 'Karyawan',
                    'value' => $model->karyawan->nama
                ],
                'keterangan_perjalanan:ntext',
                [
                    'label' => 'Mulai Perjalanan',
                    'value' => function ($model) {
                        $tanggalFormat = new Tanggal();
                        return $tanggalFormat->getIndonesiaFormatTanggal($model->tanggal_mulai);
                        // return date('d-m-Y', strtotime($model->tanggal_mulai));
                    }
                ],
                [
                    'label' => 'Selesai Pada',
                    'value' => function ($model) {
                        $tanggalFormat = new Tanggal();
                        return $tanggalFormat->getIndonesiaFormatTanggal($model->tanggal_selesai);
                        // return date('d-m-Y', strtotime($model->tanggal_selesai));
                    }
                ],
                [
                    'label' => 'Biaya Diajukan',
                    'value' => function ($model) {
                        return $model->estimasi_biaya;
                    },
                    'format' => 'currency', // Format currency untuk otomatis
                    'contentOptions' => ['style' => 'text-align: left;'], // Align text ke kanan
                ],
                [
                    'attribute' => 'biaya_yang_disetujui',
                    'format' => 'currency', // Format currency untuk otomatis
                    'contentOptions' => ['style' => 'text-align: left;'], // Align text ke kanan
                ],

                [
                    'attribute' => 'Ditanggapi Oleh',
                    'value' => function ($model) {
                        if ($model->status == 0) {
                            return '<span class="text-warning">Menuggu Tanggapan</span>';
                        }
                        $username = User::findOne(['id' => $model->disetujui_oleh])->username;
                        return  $username ?? '<span class="text-danger">User Tidak Terdaftar</span>';
                    },
                    "format" => 'raw',
                ],
                [
                    'attribute' => 'Ditanggapi Pada',
                    'value' => function ($model) {
                        if ($model->status == 0) {
                            return '<span class="text-warning">Menuggu Tanggapan</span>';
                        }

                        $tanggalFormat = new Tanggal();
                        return $tanggalFormat->getIndonesiaFormatTanggal($model->disetujui_pada) ?? '-';
                    },
                    "format" => 'raw',
                ],
                [
                    'format' => 'raw',
                    'attribute' => 'status',
                    'value' => function ($model) {
                        if ($model->status == 0) {
                            return '<span class="text-warning">Menuggu Tanggapan</span>';
                        } elseif ($model->status == 1) {
                            return '<span class="text-success">Pengajuan Telah Disetujui</span>';
                        } elseif ($model->status == 2) {
                            return '<span class="text-danger">Pengajuan  Ditolak</span>';
                        } else {
                            return "<span class='text-danger'>master kode tidak aktif</span>";
                        }
                    },
                ],
                [
                    'label' => 'Catatan Admin',
                    'value' => function ($model) {
                        return $model->catatan_admin ?? '-';
                    }
                ],
                [
                    'label' => 'Dokumentasi',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->status != 0) {
                            if ($model->files != null) {
                                $files = json_decode($model->files, true);
                                if ($files) {
                                    $output = '<ul>';
                                    foreach ($files as $key => $file) {
                                        $key++;
                                        $output .= '<li>' . Html::a("Dokumentasi {$key}",  '/panel/' . $file, ['target' => '_blank']) . '</li>';
                                    }
                                    $output .= '</ul>';
                                    return $output; // Kembalikan output yang sudah diformat
                                }
                            }
                        }
                        return ''; // Kembalikan string kosong jika tidak ada file
                    }
                ]
            ],
        ]) ?>

    </div>
</div>