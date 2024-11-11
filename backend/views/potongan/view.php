<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\Potongan $model */

$this->title = $model->nama_potongan;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Potongan'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="potongan-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container table-responsive">
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('update', ['update', 'id_potongan' => $model->id_potongan], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_potongan' => $model->id_potongan], [
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
                'nama_potongan',
            ],
        ]) ?>

    </div>
</div>