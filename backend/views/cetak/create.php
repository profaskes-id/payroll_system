<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Cetak $model */

$this->title = Yii::t('app', 'Cetak kontrak kerja ' . $karyawan->nama);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cetak'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cetak-create">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['/karyawan/view', 'id_karyawan' => $karyawan->id_karyawan], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'karyawan' => $karyawan,
        'pekerjaan' => $pekerjaan,
        'model' => $model,
        'perusahaan' => $perusahaan

    ]) ?>

</div>