<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\PendapatanPotonganLainnya $model */

$this->title = $model->id_ppl;
$this->params['breadcrumbs'][] = ['label' => 'Pendapatan Potongan Lainnyas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pendapatan-potongan-lainnya-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id_ppl' => $model->id_ppl], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id_ppl' => $model->id_ppl], [
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
            'id_ppl',
            'id_karyawan',
            'id_periode_gaji',
            'keterangan:ntext',
            'is_pendapatan',
            'is_potongan',
            'created_at',
            'updated_at',
            'jumlah',
        ],
    ]) ?>

</div>
