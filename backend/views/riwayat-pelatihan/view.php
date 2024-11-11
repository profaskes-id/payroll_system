<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\RiwayatPelatihan $model */

$this->title = 'Pelatihan ' . $model->karyawan->nama;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Riwayat Pelatihans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="riwayat-pelatihan-view">


    <div class="costume-container">
        <?php
        $id_karyawan = Yii::$app->request->get('id_karyawan');
        ?>
        <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['karyawan/view?id_karyawan=' . $model->id_karyawan], ['class' => 'costume-btn']) ?>
    </div>


    <div class="table-container table-responsive">
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Update', ['update', 'id_riwayat_pelatihan' => $model->id_riwayat_pelatihan], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_riwayat_pelatihan' => $model->id_riwayat_pelatihan], [
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
                    'attribute' => 'Karyawan',
                    'value' => function ($model) {
                        return $model->karyawan->nama;
                    }
                ],
                'judul_pelatihan',
                'tanggal_mulai',
                'tanggal_selesai',
                'penyelenggara',
                'deskripsi:ntext',
                [
                    'label' => 'Sertifikat',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->sertifikat) {
                            return Html::a('Preview  Sertifikat', Yii::getAlias('@root') . '/panel/' . $model->sertifikat, ['target' => '_blank']);
                        }
                        return 'Belum Ada Sertifikat';
                    }
                ]
            ],
        ]) ?>
    </div>
</div>