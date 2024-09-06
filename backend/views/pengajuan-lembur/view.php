<?php

use amnah\yii2\user\models\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanLembur $model */

$this->title = "Pengajuan Lembur" . ' ' . $model->karyawan->nama;
$this->params['breadcrumbs'][] = ['label' => 'Pengajuan Lembur', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pengajuan-lembur-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <ol style="margin: 0 !important; padding: 0 !important">
        <div class='table-container'>
            <p class="d-flex justify-content-start " style="gap: 10px;">
                <?= Html::a('Tanggapi', ['update', 'id_pengajuan_lembur' => $model->id_pengajuan_lembur], ['class' => 'add-button']) ?>
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
                    'id_pengajuan_lembur',
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

                    'jam_mulai',
                    'jam_selesai',
                    [

                        'label' => 'Tanggal',
                        'value' => function ($model) {
                            return date('d-m-Y', strtotime($model->tanggal));
                        }

                    ],
                    [
                        'attribute' => 'Ditanggapi Oleh',
                        'value' => function ($model) {
                            if ($model->status == 0) {
                                return '<span class="text-warning">Menuggu Tanggapan</span>';
                            }
                            $username = User::findOne(['id' => $model->disetujui_oleh])->username;
                            return  $username ?? '<span class="text-danger">User Tidak Terdaftar</span>';
                        },
                        "format" => 'raw',
                    ],
                    [
                        'attribute' => 'Ditanggapi Pada',
                        'value' => function ($model) {
                            if ($model->status == 0) {
                                return '<span class="text-warning">Menuggu Tanggapan</span>';
                            }
                            return date('d-m-Y', strtotime($model->disetujui_pada)) ?? '<span class="text-danger">-</span>';
                        },
                        "format" => 'raw',
                    ],
                    [
                        'format' => 'raw',
                        'attribute' => 'status',
                        'value' => function ($model) {
                            if ($model->status == 0) {
                                return '<span class="text-warning">Menuggu Tanggapan</span>';
                            } elseif ($model->status == 1) {
                                return '<span class="text-success">Pengajuan Telah Disetujui</span>';
                            } elseif ($model->status == 2) {
                                return '<span class="text-danger">Pengajuan  Ditolak</span>';
                            } else {
                                return "<span class='text-danger'>master kode tidak aktif</span>";
                            }
                        },
                    ],
                ],
            ]) ?>

        </div>
    </ol>
</div>