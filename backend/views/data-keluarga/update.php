<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\DataKeluarga $model */

$this->title = 'Update Data Keluarga: ' . $model->id_data_keluarga;
$this->params['breadcrumbs'][] = ['label' => 'Data keluarga', 'url' => ['karyawan/view', 'id_karyawan' => $model->id_karyawan]];
$this->params['breadcrumbs'][] = ['label' => $model->id_data_keluarga, 'url' => ['view', 'id_data_keluarga' => $model->id_data_keluarga]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="data-keluarga-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['karyawan/view', 'id_karyawan' => $model->id_karyawan], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>