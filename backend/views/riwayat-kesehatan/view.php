<?php

use backend\models\Tanggal;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\RiwayatKesehatan $model */

$this->title = "Kesehatan: " . $model->karyawan->nama;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Riwayat Kesehatan'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="riwayat-kesehatan-view">

    <div class="costume-container">
        <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['karyawan/view?id_karyawan=' . $model->id_karyawan], ['class' => 'costume-btn']) ?>
    </div>

    <div class="table-container table-responsive">
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Update', ['update', 'id_riwayat_kesehatan' => $model->id_riwayat_kesehatan], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_riwayat_kesehatan' => $model->id_riwayat_kesehatan], [
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
                'nama_pengecekan',
                'keterangan:ntext',
                [
                    'label' => 'Tanggal',
                    'value' => function ($model) {
                        $tanggalFormat = new Tanggal();
                        return $tanggalFormat->getIndonesiaFormatTanggal($model->tanggal);
                        // return date('d M Y', strtotime($model->tanggal));
                    }
                ],
                [
                    'label' => 'Surat Keterangan Dokter',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->surat_dokter) {
                            return Html::a('Preview Surat Dokter', Yii::getAlias('@root') . '/panel/' . $model->surat_dokter, ['target' => '_blank']);
                        }
                        return 'Belum Ada Surat Dokter';
                    }
                ]
            ],
        ]) ?>

    </div>
</div>