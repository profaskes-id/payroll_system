<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\TunjanganDetail $model */

$this->title = $model->id_tunjangan_detail;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tunjangan Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="tunjangan-detail-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id_tunjangan_detail' => $model->id_tunjangan_detail], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id_tunjangan_detail' => $model->id_tunjangan_detail], [
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
            'id_tunjangan_detail',
            'id_tunjangan',
            'id_karyawan',
            'jumlah',
        ],
    ]) ?>

</div>
