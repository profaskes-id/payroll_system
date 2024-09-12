<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanLembur $model */

$this->title = $model->karyawan->nama . " (" . date('d-M-Y', strtotime($model->tanggal)) . ")";
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pengajuan Lemburs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pengajuan-lembur-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <div class='table-container'>

        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?php // Html::a('Update', ['update', 'id_pengajuan_lembur' => $model->id_pengajuan_lembur], ['class' => 'add-button']) 
            ?>
            <?= Html::a('Delete', ['delete', 'id_pengajuan_lembur' => $model->id_pengajuan_lembur], [
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
                    'label' => 'karyawan',
                    'value' => function ($model) {
                        return $model->karyawan->nama;
                    }
                ],
                [
                    'label' => 'Pekerjaan',
                    'format' => 'raw',
                    'value' => function ($model) {
                        $poinArray = json_decode($model->pekerjaan ?? []);
                        $finalValue = [];
                        foreach ($poinArray as $item) {
                            $finalValue[] = "<li style='margin-left: 20px'>$item</li>";
                        }
                        return implode('', $finalValue);
                    }
                ],
                'status',
                'jam_mulai',
                'jam_selesai',
                [
                    'label' => 'Tanggal',
                    'value' => function ($model) {
                        return date('d-M-Y', strtotime($model->tanggal));
                    }
                ],
                'disetujui_oleh',
                'disetujui_pada',
            ],
        ]) ?>

    </div>