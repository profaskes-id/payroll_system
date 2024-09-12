<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\DataPekerjaan $model */

$this->title = $model->karyawan->nama . ' (' . $model->bagian->nama_bagian . ')';
$this->params['breadcrumbs'][] = ['label' => 'Data pekerjaan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="data-pekerjaan-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['karyawan/view?id_karyawan=' . $model->id_karyawan], ['class' => 'costume-btn']) ?>
        </p>
    </div>


    <div class='table-container'>

        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Update', ['update', 'id_data_pekerjaan' => $model->id_data_pekerjaan], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_data_pekerjaan' => $model->id_data_pekerjaan], [
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
                [
                    'label' => 'Bagian',
                    'value' => $model->bagian->nama_bagian
                ],
                'dari',
                [
                    'attribute' => 'sampai',
                    'value' => function ($model) {
                        if ($model->is_currenty != null) {
                            return 'Sekarang';
                        }
                        return date('d-m-Y', strtotime($model->sampai));
                    }
                ],
                [
                    'attribute' => 'status',
                    'value' => function ($model) {
                        return $model->statusPekerjaan->nama_kode;
                    }
                ],
                [
                    'label' => 'Jabatan',
                    'value' => $model->jabatanPekerja->nama_kode
                ],
                [
                    'label' => 'Gaji Pokok',
                    'value' => function ($model) {

                        // Set locale to Indonesian
                        $locale = 'id_ID';
                        $fmt = new NumberFormatter($locale, NumberFormatter::CURRENCY);

                        // Format the number to IDR
                        $amount = (int)$model->gaji_pokok;
                        return $fmt->formatCurrency($amount, 'IDR'); // Output: Rp2.800.000,00

                    }
                ],
                [
                    'label' => 'Terbilang',
                    'contentOptions' => ['style' => 'text-transform: capitalize; '],
                    'value' => $model->terbilang
                ],
                [
                    'label' => 'Surat Lamaran Pekerjaan',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->surat_lamaran_pekerjaan) {
                            return Html::a('Preview Surat Lamaran Pekerjaan', Yii::getAlias('@root') . '/panel/' . $model->surat_lamaran_pekerjaan, ['target' => '_blank']);
                        }
                        return 'Belum Ada Surat Lamaran Pekerjaan';
                    }
                ]
            ],
        ]) ?>
    </div>


</div>