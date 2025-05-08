<?php

use backend\models\Tanggal;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanShift $model */

$this->title = "penggantian shift oleh : " . $model->karyawan->nama;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pengajuan Shifts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$tanggalFormat = new Tanggal();

?>
<div class="pengajuan-shift-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div style="margin: 0 !important; padding: 0 !important">
        <div class="table-container table-responsive">
            <p class="d-flex justify-content-start " style="gap: 10px;">
                <?= Html::a('Tanggapi', ['update', 'id_pengajuan_shift' => $model->id_pengajuan_shift], ['class' => 'add-button']) ?>
                <?= Html::a('Delete', ['delete', 'id_pengajuan_shift' => $model->id_pengajuan_shift], [
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
                        'label' => "Karyawan",
                        'value' => function ($model) {
                            return $model->karyawan->nama;
                        },
                    ],
                    [
                        'label' => "Shift Kerja",
                        'value' => function ($model) {
                            return $model->shiftKerja->nama_shift;
                        },
                    ],
                    [
                        'label' => "Tanggal Awal",
                        'value' => function ($model) use ($tanggalFormat) {
                            if ($model->tanggal_awal != null) {
                                return $tanggalFormat->getIndonesiaFormatTanggal($model->tanggal_awal);
                            }
                        }
                    ],
                    [
                        'label' => "Tanggal",
                        'value' => function ($model) use ($tanggalFormat) {
                            if ($model->tanggal_akhir != null) {
                                return $tanggalFormat->getIndonesiaFormatTanggal($model->tanggal_akhir);
                            }
                        }
                    ],
                    [
                        'label' => "Tanggal",
                        'value' => function ($model) use ($tanggalFormat) {
                            if ($model->diajukan_pada != null) {
                                return $tanggalFormat->getIndonesiaFormatTanggal($model->diajukan_pada);
                            }
                        }
                    ],

                    [
                        'format' => 'raw',
                        'attribute' => 'status',
                        'value' => function ($model) {
                            if ($model->status !== null) {
                                if (strtolower($model->status) == 0) {
                                    return "<span class='text-capitalize text-warning '>Pending</span>";
                                } elseif (strtolower($model->status) == 1) {
                                    return "<span class='text-capitalize text-success '>disetujui</span>";
                                } elseif (strtolower($model->status) == 2) {

                                    return "<span class='text-capitalize text-danger '>ditolak</span>";
                                }
                            }
                        }
                    ],
                    'ditanggapi_oleh',
                    'ditanggapi_pada',
                    'catatan_admin:ntext',
                ],
            ]) ?>

        </div>
    </div>
</div>