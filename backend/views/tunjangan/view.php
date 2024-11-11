<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\Tunjangan $model */

$this->title = $model->nama_tunjangan;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tunjangan'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="tunjangan-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container table-responsive">
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('update', ['update', 'id_tunjangan' => $model->id_tunjangan], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_tunjangan' => $model->id_tunjangan], [
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
                'nama_tunjangan',
            ],
        ]) ?>

    </div>
</div>