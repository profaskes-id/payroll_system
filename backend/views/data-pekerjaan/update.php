<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\DataPekerjaan $model */

$this->title = 'Data Pekerjaan: ' . $model->bagian->nama_bagian;
$this->params['breadcrumbs'][] = ['label' => 'Data pekerjaan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_data_pekerjaan, 'url' => ['view', 'id_data_pekerjaan' => $model->id_data_pekerjaan]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="data-pekerjaan-update">

    <div class="costume-container">

        <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['karyawan/view?id_karyawan=' . $model->id_karyawan], ['class' => 'costume-btn']) ?>
    </div>


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>