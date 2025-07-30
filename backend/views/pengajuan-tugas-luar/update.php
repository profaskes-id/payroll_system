<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanTugasLuar $model */

$this->title = Yii::t('app', 'Update Pengajuan Tugas Luar: {name}', [
    'name' => $model->karyawan->nama,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pengajuan Tugas Luars'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_tugas_luar, 'url' => ['view', 'id_tugas_luar' => $model->id_tugas_luar]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="pengajuan-tugas-luar-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
