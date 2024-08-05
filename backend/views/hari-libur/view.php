<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\HariLibur $model */

$this->title = $model->id_hari_libur;
$this->params['breadcrumbs'][] = ['label' => 'Hari libur', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="hari-libur-view">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>


    <div class='table-container'>

        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Update', ['update', 'id_hari_libur' => $model->id_hari_libur], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_hari_libur' => $model->id_hari_libur], [
                'class' => 'reset-button',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id_hari_libur',
                'tanggal',
                'nama_hari_libur',
            ],
        ]) ?>
    </div>

</div>