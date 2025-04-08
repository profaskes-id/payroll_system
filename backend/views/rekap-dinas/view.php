<?php

use backend\models\Tanggal;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanDinas $model */

$this->title = $model->karyawan->nama . " (" . date('d-M-Y', strtotime($model->tanggal_mulai)) . ")";
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pengajuan Dinas'), 'url' => ['index']];
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
            <?php // Html::a('update', ['update', 'id_pengajuan_dinas' => $model->id_pengajuan_dinas], ['class' => 'add-button']) 
            ?>
            <?= Html::a('Delete', ['delete', 'id_pengajuan_dinas' => $model->id_pengajuan_dinas], [
                'class' => 'reset-button',
                'data' => [
                    'confirm' => 'Apakah Anda Yakin Ingin Menghapus Item Ini ?',
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
                    'label' => 'Tanggal Mulai',
                    'value' => function ($model) {
                        $tanggalFormat = new Tanggal();
                        if ($model->tanggal_mulai == null) {
                            return '-';
                        }
                        return $tanggalFormat->getIndonesiaFormatTanggal($model->tanggal_mulai);
                        // return date('d-M-Y', strtotime($model->tanggal_mulai));
                    }
                ],
                [
                    'label' => 'Tanggal Mulai',
                    'value' => function ($model) {
                        $tanggalFormat = new Tanggal();
                        if ($model->tanggal_selesai == null) {
                            return '-';
                        }
                        return $tanggalFormat->getIndonesiaFormatTanggal($model->tanggal_selesai);
                        // return date('d-M-Y', strtotime($model->tanggal_selesai));
                    }
                ],
                [
                    'attribute' => 'estimasi_biaya',
                    'format' => 'currency', // Format currency untuk otomatis
                    'contentOptions' => ['style' => 'text-align: left;'], // Align text ke kanan
                ],
                [
                    'attribute' => 'biaya_yang_disetujui',
                    'format' => 'currency', // Format currency untuk otomatis
                    'contentOptions' => ['style' => 'text-align: left;'], // Align text ke kanan
                ],
                [
                    'label' => 'Ditanggapi Oleh',
                    'value' => function ($model) {
                        return $model->user->username ?? '';
                    }
                ],
                [
                    'label' => 'Ditanggapi Pada',
                    'value' => function ($model) {
                        $tanggalFormat = new Tanggal();
                        if ($model->disetujui_pada == null) {
                            return "Belum Di set";
                        }
                        return $tanggalFormat->getIndonesiaFormatLong($model->disetujui_pada);
                    }
                ],

                [
                    'label' => 'Status',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->status == 0) {
                            return '<span class="text-warning">Menuggu Tanggapan</span>';
                        } elseif ($model->status == 1) {
                            return '<span class="text-success">Disetujui</span>';
                        } elseif ($model->status == 2) {
                            return '<span class="text-danger">Ditolak</span>';
                        } else {
                            return "<span class='text-danger'>master kode tidak aktif</span>";
                        }
                    }
                ],
                [
                    'label' => 'Dokumentasi',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->status != 0) {
                            if ($model->dokumentasi != null) {
                                $files = json_decode($model->dokumentasi, true);
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