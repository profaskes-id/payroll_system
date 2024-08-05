<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\JamKerja $model */

$this->title = $model->id_jam_kerja;
$this->params['breadcrumbs'][] = ['label' => 'Jam kerja', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="jam-kerja-view">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class='table-container'>
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Update', ['update', 'id_jam_kerja' => $model->id_jam_kerja], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_jam_kerja' => $model->id_jam_kerja], [
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
                'id_jam_kerja',
                'nama_jam_kerja',
            ],
        ]) ?>
    </div>

</div>