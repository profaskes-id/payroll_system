<?php

use backend\models\Tanggal;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\IzinPulangCepat $model */

$this->title = "Pulang Cepat - " . $model->karyawan->nama;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Izin Pulang Cepat'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="izin-pulang-cepat-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container table-responsive">
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Tanggapi', ['update', 'id_izin_pulang_cepat' => $model->id_izin_pulang_cepat], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_izin_pulang_cepat' => $model->id_izin_pulang_cepat], [
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
                    'value' => function ($model) {
                        return $model->karyawan->nama;
                    }
                ],
                'alasan:ntext',
                [
                    'label' => 'Ditanggapi Oleh',
                    'value' => function ($model) {
                        return $model->user->username ?? '-';
                    }
                ],
                [
                    'label' => 'Ditanggapi Pada',
                    'value' => function ($model) {
                        if ($model->disetujui_pada) {
                            $tanggalFormat = new Tanggal();
                            return $tanggalFormat->getIndonesiaFormatTanggal($model->disetujui_pada);
                        } else {
                            return '-';
                        }
                    }
                ],
                [

                    'label' => 'Status',
                    'format' => 'raw',
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
            ],
        ]) ?>

    </div>