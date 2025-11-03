<?php

use amnah\yii2\user\models\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = 'Kasbon : ' .  $model->karyawan->nama;
$this->params['breadcrumbs'][] = ['label' => 'Pengajuan Kasbon', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pengajuan-kasbon-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container table-responsive">
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Tanggapi', ['update', 'id_pengajuan_kasbon' => $model->id_pengajuan_kasbon], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_pengajuan_kasbon' => $model->id_pengajuan_kasbon], [
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
                    'label' => 'Nama',
                    'value' => function ($model) {
                        return $model->karyawan->nama;
                    }
                ],
                [
                    'label' => 'Gaji Pokok',
                    'value' => function ($model) {
                        return 'Rp ' . number_format($model->gaji_pokok, 0, ',', '.');
                    }
                ],
                [
                    'label' => 'Jumlah Kasbon',
                    'value' => function ($model) {
                        return 'Rp ' . number_format($model->jumlah_kasbon, 0, ',', '.');
                    }
                ],
                [
                    'label' => 'Tanggal Pengajuan',
                    'value' => function ($model) {
                        return Yii::$app->formatter->asDate($model->tanggal_pengajuan, 'php:d-m-Y');
                    }
                ],
                [
                    'label' => 'Tanggal Pencairan',
                    'value' => function ($model) {
                        return $model->tanggal_pencairan ? Yii::$app->formatter->asDate($model->tanggal_pencairan, 'php:d-m-Y') : '-';
                    }
                ],
                [
                    'label' => 'lama cicilan',
                    'value' => function ($model) {
                        return $model->lama_cicilan . ' Bulan';
                    }
                ],
                [
                    'label' => 'Angsuran Perbulan',
                    'value' => function ($model) {
                        return 'Rp ' . number_format($model->angsuran_perbulan, 0, ',', '.');
                    }
                ],
                [
                    'label' => 'Tanggal Mulai Potong',
                    'value' => function ($model) {
                        return $model->tanggal_mulai_potong ? Yii::$app->formatter->asDate($model->tanggal_mulai_potong, 'php:d-m-Y') : '-';
                    }
                ],
                'keterangan:ntext',
                [
                    'label' => 'Tanggal Disetujui',
                    'value' => function ($model) {
                        return $model->tanggal_disetujui ? Yii::$app->formatter->asDate($model->tanggal_disetujui, 'php:d-m-Y') : '-';
                    }
                ],

                [
                    'label' => 'Auto Debt',
                    'value' => function ($model) {
                        return $model->tipe_potongan == 0 ? 'Off' : 'On';
                    }
                ],
                [
                    'label' => 'Created At',
                    'value' => function ($model) {
                        return $model->created_at ? date('d-m-Y H:i', $model->created_at) : '-';
                    }
                ],
                [
                    'label' => 'Created By',
                    'value' => function ($model) {
                        if (!$model->created_by) {
                            return '<span class="text-danger">User Tidak Terdaftar</span>';
                        }
                        $username = User::findOne($model->created_by)->username ?? '';
                        return $username ?: '<span class="text-danger">User Tidak Terdaftar</span>';
                    },
                    'format' => 'raw',
                ],
                [
                    'label' => 'Updated At',
                    'value' => function ($model) {
                        return $model->updated_at ? date('d-m-Y H:i', $model->updated_at) : '-';
                    }
                ],
                [
                    'label' => 'Updated By',
                    'value' => function ($model) {
                        if (!$model->updated_by) {
                            return '<span class="text-danger">User Tidak Terdaftar</span>';
                        }
                        $username = User::findOne($model->updated_by)->username ?? '';
                        return $username ?: '<span class="text-danger">User Tidak Terdaftar</span>';
                    },
                    'format' => 'raw',
                ],
                [
                    'format' => 'raw',
                    'attribute' => 'status',
                    'value' => function ($model) {
                        if ($model->status == 0) {
                            return '<span class="text-warning">Menunggu Tanggapan</span>';
                        } elseif ($model->status == 1) {
                            return '<span class="text-success">Pengajuan Telah Disetujui</span>';
                        } elseif ($model->status == 2) {
                            return '<span class="text-danger">Pengajuan Ditolak</span>';
                        } else {
                            return "<span class='text-danger'>Kode Status Tidak Aktif</span>";
                        }
                    },
                ],
            ],
        ]) ?>


    </div>
</div>