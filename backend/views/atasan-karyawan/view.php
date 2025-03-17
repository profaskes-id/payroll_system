<?php

use amnah\yii2\user\models\User;
use backend\models\Karyawan;
use backend\models\Tanggal;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\AtasanKaryawan $model */

$this->title = $model->karyawan->nama;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Atasan Karyawans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="atasan-karyawan-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container table-responsive">
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Update', ['update', 'id_atasan_karyawan' => $model->id_atasan_karyawan], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_atasan_karyawan' => $model->id_atasan_karyawan], [
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
                    'attribute' => 'karyawan.nama',
                ],
                [
                    'format' => 'raw',
                    'label' => 'Atasan',
                    'value' => function ($model) {
                        // Jika model tidak ada, kembalikan pesan "Belum Di Set"
                        // dd($model);
                        if (empty($model) || empty($model['id_atasan'])) {
                            return '<p class="text-danger">(Belum Di Set)</p>';
                        }
                        $data = Karyawan::find()->select('nama')->where(['id_karyawan' => $model['id_atasan']])->one();
                        // Kembalikan nama lengkap atau pesan "Belum Di Set" jika tidak ada
                        return $data->nama ?? '<p class="text-danger">(Belum Di Set)</p>';
                    }
                ],

                [
                    'label' => 'Di Set Oleh',
                    'value' => function ($model) {
                        $user = User::findOne($model->di_setting_oleh);
                        return $user->profile->full_name ?? $user->email;
                    },
                ],
                [
                    'label' => 'Di Set Pada',
                    'value' => function ($model) {
                        $tanggalFormat = new Tanggal();
                        return $tanggalFormat->getIndonesiaFormatTanggal($model->di_setting_pada);
                        // return date('d-M-Y - H:i', strtotime($model->di_setting_pada));
                    },
                ],
                [
                    'label' => 'Penampatan',
                    'attribute' => 'masterLokasi.label',
                ]
            ],
        ]) ?>

    </div>