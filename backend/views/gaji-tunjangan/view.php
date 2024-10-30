<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\GajiTunjangan $model */

$this->title = $model->id_gaji_tunjangan;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Gaji Tunjangans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="gaji-tunjangan-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id_gaji_tunjangan' => $model->id_gaji_tunjangan], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id_gaji_tunjangan' => $model->id_gaji_tunjangan], [
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
            'id_gaji_tunjangan',
            'id_tunjangan_detail',
            'nama_tunjangan',
            'jumlah',
        ],
    ]) ?>

</div>
