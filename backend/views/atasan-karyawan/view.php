<?php

use amnah\yii2\user\models\User;
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
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container">
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Update', ['update', 'id_atasan_karyawan' => $model->id_atasan_karyawan], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_atasan_karyawan' => $model->id_atasan_karyawan], [
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
                    'attribute' => 'karyawan.nama',
                ],
                [
                    'label' => 'Atasan',
                    'attribute' => 'atasan.nama',
                ],
                [
                    'label' => 'Status',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->status == 0) {
                            return '<p class="text-danger m-0 p-0">Tidak Aktif</p>';
                        } else {
                            return '<p class="text-success m-0 p-0">Aktif</p>';
                        }
                    },
                ],
                [
                    'label' => 'Di Set Oleh',
                    'value' => function ($model) {
                        $user = User::findOne($model->di_setting_oleh);
                        return $user->username ?? $user->email;
                    },
                ],
                [
                    'label' => 'Di Set Pada',
                    'value' => function ($model) {
                        return date('d-M-Y - H:i', strtotime($model->di_setting_pada));
                    },
                ],
            ],
        ]) ?>

    </div>