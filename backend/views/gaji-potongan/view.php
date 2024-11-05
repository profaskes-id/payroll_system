<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\GajiPotongan $model */

$this->title = $model->id_gaji_potongan;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Gaji Potongan'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="gaji-potongan-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id_gaji_potongan' => $model->id_gaji_potongan], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id_gaji_potongan' => $model->id_gaji_potongan], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_gaji_potongan',
            'id_potongan_detail',
            'nama_potongan',
            'jumlah',
            'id_transaksi_gaji',
        ],
    ]) ?>

</div>