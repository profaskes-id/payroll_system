<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\DataPekerjaan $model */

$this->title = $model->id_data_pekerjaan;
$this->params['breadcrumbs'][] = ['label' => 'Data pekerjaan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="data-pekerjaan-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>


    <div class='table-container'>

        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Update', ['update', 'id_data_pekerjaan' => $model->id_data_pekerjaan], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_data_pekerjaan' => $model->id_data_pekerjaan], [
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
                'id_data_pekerjaan',
                'id_karyawan',
                'id_bagian',
                'dari',
                'sampai',
                'status',
                'jabatan',
            ],
        ]) ?>
    </div>


</div>