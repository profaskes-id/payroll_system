<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\RiwayatPendidikan $model */

$this->title = 'Tambah Riwayat Pendidikan';
$this->params['breadcrumbs'][] = ['label' => 'Riwayat pendidikan', 'url' => ['karyawan/view', 'id_karyawan' => $model->id_karyawan]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="riwayat-pendidikan-create">

    <div class="costume-container">
        <?php
        $id_karyawan = Yii::$app->request->get('id_karyawan');
        ?>
        <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['karyawan/view?id_karyawan=' . $id_karyawan], ['class' => 'costume-btn']) ?>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>