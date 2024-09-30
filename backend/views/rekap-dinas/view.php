<?php

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

    <div class='table-container'>
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?php // Html::a('update', ['update', 'id_pengajuan_dinas' => $model->id_pengajuan_dinas], ['class' => 'add-button']) 
            ?>
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
                    'label' => 'Tanggal Mulai',
                    'value' => function ($model) {
                        return date('d-M-Y', strtotime($model->tanggal_mulai));
                    }
                ],
                [
                    'label' => 'Tanggal Mulai',
                    'value' => function ($model) {
                        return date('d-M-Y', strtotime($model->tanggal_selesai));
                    }
                ],
                'estimasi_biaya',
                'biaya_yang_disetujui',
                [
                    'label' => 'Disetujui Oleh',
                    'value' => function ($model) {
                        return $model->user->username ?? '';
                    }
                ],
                [
                    'label' => 'Disetujui Pada',
                    'value' => function ($model) {
                        return date('d-m-Y H:i', strtotime($model->disetujui_pada));
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
                ]
            ],
        ]) ?>

    </div>
</div>