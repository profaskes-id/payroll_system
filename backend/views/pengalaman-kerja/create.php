<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\PengalamanKerja $model */

$this->title = 'Tambah Pengalaman Kerja';
$this->params['breadcrumbs'][] = ['label' => 'Pengalaman kerja', 'url' => ['karyawan/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengalaman-kerja-create">

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