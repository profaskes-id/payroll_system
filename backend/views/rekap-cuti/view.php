<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\RekapCuti $model */

$this->title = $model->id_rekap_cuti;
$this->params['breadcrumbs'][] = ['label' => 'Rekap Cutis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="rekap-cuti-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id_rekap_cuti' => $model->id_rekap_cuti], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id_rekap_cuti' => $model->id_rekap_cuti], [
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
            'id_rekap_cuti',
            'id_master_cuti',
            'id_karyawan',
            'total_hari_terpakai',
        ],
    ]) ?>

</div>
