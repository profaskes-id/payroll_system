<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\MasterCuti $model */

$this->title = $model->id_master_cuti;
$this->params['breadcrumbs'][] = ['label' => 'Master Cutis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="master-cuti-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id_master_cuti' => $model->id_master_cuti], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id_master_cuti' => $model->id_master_cuti], [
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
            'id_master_cuti',
            'jenis_cuti',
            'deskripsi_singkat:ntext',
            'total_hari_pertahun',
            'status',
        ],
    ]) ?>

</div>
