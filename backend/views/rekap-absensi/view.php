<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\Absensi $model */

$this->title = $model->id_absensi;
$this->params['breadcrumbs'][] = ['label' => 'Absensis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="absensi-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id_absensi' => $model->id_absensi], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id_absensi' => $model->id_absensi], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_absensi',
            'id_karyawan',
            'tanggal',
            'jam_masuk',
            'jam_pulang',
            'kode_status_hadir',
            'keterangan:ntext',
            'lampiran',
        ],
    ]) ?>

</div>
