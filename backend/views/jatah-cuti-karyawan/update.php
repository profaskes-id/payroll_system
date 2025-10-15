<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\JatahCutiKaryawan $model */

$this->title = 'Update Jatah Cuti Karyawan: ' . $model->id_jatah_cuti;
$this->params['breadcrumbs'][] = ['label' => 'Jatah Cuti Karyawans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_jatah_cuti, 'url' => ['view', 'id_jatah_cuti' => $model->id_jatah_cuti]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="jatah-cuti-karyawan-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>