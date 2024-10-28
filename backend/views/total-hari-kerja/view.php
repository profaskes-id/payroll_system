<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\TotalHariKerja $model */


$months = [
    'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember',
];

$this->title = $model->jamKerja->nama_jam_kerja . ' - ' . $months[$model->bulan - 1];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Total Hari Kerjas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="total-hari-kerja-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div style="margin: 0 !important; padding: 0 !important">
        <div class="table-container table-responsive">
            <p class="d-flex justify-content-start " style="gap: 10px;">
                <?= Html::a('Update', ['update', 'id_total_hari_kerja' => $model->id_total_hari_kerja], ['class' => 'add-button']) ?>
                <?= Html::a('Delete', ['delete', 'id_total_hari_kerja' => $model->id_total_hari_kerja], [
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
                        'label' => 'Jam Kerja',
                        'value' => $model->jamKerja->nama_jam_kerja
                    ],
                    'total_hari',
                    [
                        'attribute' => 'bulan',
                        'value' => function ($model) use ($months) {


                            return $months[$model->bulan - 1];
                        }
                    ],
                    'tahun',
                    'keterangan',
                    [
                        'attribute' => 'status',
                        'value' => function ($model) {
                            if ($model->is_aktif == 1) {
                                return "Aktif";
                            } else {
                                return "Tidak Aktif";
                            }
                        }
                    ]
                ],
            ]) ?>

        </div>
    </div>
</div>