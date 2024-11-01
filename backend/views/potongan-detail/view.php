<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\PotonganDetail $model */

$this->title = $model->id_potongan_detail;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Potongan Detail'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="potongan-detail-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container table-responsive">
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('update', ['update', 'id_potongan_detail' => $model->id_potongan_detail], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_potongan_detail' => $model->id_potongan_detail], [
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

                [
                    'label' => "Karyawan",
                    'value' => function ($model) {
                        return $model->karyawan->nama;
                    },
                ],
                'jumlah',
            ],
        ]) ?>

    </div>
</div>