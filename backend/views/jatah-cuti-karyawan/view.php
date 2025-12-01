<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\JatahCutiKaryawan $model */

$this->title = $model->karyawan->nama ?? $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Jatah Cuti Karyawan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="jatah-cuti-karyawan-view">



    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container table-responsive">
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Update', ['update', 'id_jatah_cuti' => $model->id_jatah_cuti, 'id_karyawan' => $model->id_karyawan], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_jatah_cuti' => $model->id_jatah_cuti], [
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
                    'value' => function ($model) {
                        return $model->karyawan->nama;
                    },
                ],
                [
                    'label' => 'Jatah Cuti',
                    'value' => function ($model) {
                        return $model->masterCuti->jenis_cuti ?? $model->id_jenis_cuti;
                    },
                ],
                'jatah_hari_cuti',
                'tahun',
                'created_at',
                [
                    'label' => 'Created By',
                    'value' => function ($model) {

                        return $model->createdby->profile->full_name ?? $model->createdby->username ?? 'Belum disetujui';
                    },
                ],
                'updated_at',
                [
                    'label' => 'Updated By',
                    'value' => function ($model) {

                        return $model->updatedby->profile->full_name ?? $model->updatedby->username ?? 'Belum disetujui';
                    },
                ],

            ],
        ]) ?>

    </div>
</div>