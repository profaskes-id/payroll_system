<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Cetak $model */

$this->title = Yii::t('app', 'Update data kontrak : {name}', [
    'name' => $model->karyawan->nama,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cetaks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->karyawan->nama, 'url' => ['view', 'id_cetak' => $model->id_cetak]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="cetak-update">

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