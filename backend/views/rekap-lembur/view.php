<?php

use backend\models\Tanggal;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanLembur $model */

$this->title = $model->karyawan->nama . " (" . date('d-M-Y', strtotime($model->tanggal)) . ")";
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pengajuan Lembur'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pengajuan-lembur-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <div class="table-container table-responsive">

        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?php // Html::a('Update', ['update', 'id_pengajuan_lembur' => $model->id_pengajuan_lembur], ['class' => 'add-button']) 
            ?>
            <?= Html::a('Delete', ['delete', 'id_pengajuan_lembur' => $model->id_pengajuan_lembur], [
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
                    'label' => 'karyawan',
                    'value' => function ($model) {
                        return $model->karyawan->nama;
                    }
                ],
                [
                    'label' => 'Pekerjaan',
                    'format' => 'raw',
                    'value' => function ($model) {
                        $poinArray = json_decode($model->pekerjaan ?? []);
                        if ($poinArray) {
                            $finalValue = [];
                            foreach ($poinArray as $item) {
                                $finalValue[] = "<li style='margin-left: 20px'>$item</li>";
                            }
                            return implode('', $finalValue);
                        } else {
                            return 'belum di set';
                        }
                    }
                ],
                [
                    'format' => 'raw',
                    'label' => 'Status',
                    'value' => function ($model) {
                        if ($model->statusPengajuan->nama_kode !== null) {
                            if (strtolower($model->statusPengajuan->nama_kode) == "pending") {
                                return "<span class='text-capitalize text-warning '>Pending</span>";
                            } elseif (strtolower($model->statusPengajuan->nama_kode) == "disetujui") {
                                return "<span class='text-capitalize text-success '>disetujui</span>";
                            } elseif (strtolower($model->statusPengajuan->nama_kode) == "ditolak") {
                                return "<span class='text-capitalize text-danger '>ditolak</span>";
                            }
                        } else {
                            return "<span class='text-danger'>master kode tidak aktif</span>";
                        }
                    },
                ],
                [
                    'label' => 'Jam Mulai',
                    'value' => function ($model) {
                        return date('H:i', strtotime($model->jam_mulai));
                    }
                ],
                [
                    'label' => 'Jam Selesai',
                    'value' => function ($model) {
                        return date('H:i', strtotime($model->jam_selesai));
                    }
                ],
                [
                    'label' => 'Tanggal',
                    'value' => function ($model) {
                        $tanggalFormat = new Tanggal();
                        return $tanggalFormat->getIndonesiaFormatTanggal($model->tanggal);
                        // return date('d-M-Y', strtotime($model->tanggal));
                    }
                ],
                [
                    'label' => 'Ditanggapi Oleh',
                    'value' => function ($model) {
                        return $model->disetujuiOleh->username ?? 'Admin';
                    }
                ],
                [
                    'label' => 'Tanggal',
                    'value' => function ($model) {
                        $tanggalFormat = new Tanggal();
                        return $tanggalFormat->getIndonesiaFormatTanggal($model->disetujui_pada);
                        // return date('d-M-Y', strtotime($model->disetujui_pada));
                    }
                ],
            ],
        ]) ?>

    </div>