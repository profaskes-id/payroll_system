<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanCuti $model */

$this->title = $model->id_pengajuan_cuti;
$this->params['breadcrumbs'][] = ['label' => 'Pengajuan Cutis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pengajuan-cuti-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id_pengajuan_cuti' => $model->id_pengajuan_cuti], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id_pengajuan_cuti' => $model->id_pengajuan_cuti], [
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
            'id_pengajuan_cuti',
            'id_karyawan',
            'tanggal_pengajuan',
            'tanggal_mulai',
            'tanggal_selesai',
            'alasan_cuti:ntext',
            'status',
            'catatan_admin:ntext',
        ],
    ]) ?>

</div>
