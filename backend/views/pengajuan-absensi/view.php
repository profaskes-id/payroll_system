<?php

use amnah\yii2\user\models\User;
use backend\models\Tanggal;
use yii\helpers\Html;
use yii\widgets\DetailView;

$tanggalFormat = new Tanggal();

/** @var yii\web\View $this */
/** @var backend\models\PengajuanAbsensi $model */

$this->title = "Pengajuan Absensi " . $model->karyawan->nama;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pengajuan Absensis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pengajuan-absensi-view">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container table-responsive">
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Tanggapi', ['update', 'id' => $model->id], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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

                [
                    'attribute' => 'Tanggal Absen',
                    'value' => function ($model)  {
    return $model->tanggal_absen ?? '-';
                    }
                ],
                'jam_masuk',
                'jam_keluar',
                'alasan_pengajuan:ntext',
                [
                    'attribute' => 'Diajukan Pada',
                    'value' => function ($model)  {

                        return $model->tanggal_pengajuan ?? '-';
                    }
                ],

                [
                    'attribute' => 'Ditanggapi Oleh',
                    'value' => function ($model) {
                        if ($model->status == 0) {
                            return '<span class="text-warning">Menuggu Tanggapan</span>';
                        }
                        $username = User::findOne(['id' => $model->id_approver])->username ?? '';
                        return  $username ?? '<span class="text-danger">User Tidak Terdaftar</span>';
                    },
                    "format" => 'raw',
                ],
                [
                    'format' => 'raw',
                    'attribute' => 'Ditanggapi Pada',
                    'value' => function ($model) use ($tanggalFormat) {
                        if ($model->status == 0) {
                            return '<span class="text-warning">Menuggu Tanggapan</span>';
                        }
                        return $model->tanggal_disetujui ?? '-';
                    }
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
                'catatan_approver:ntext',
            ],
        ]) ?>

    </div>
</div>