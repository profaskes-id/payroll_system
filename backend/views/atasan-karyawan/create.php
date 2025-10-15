<?php

use backend\models\helpers\KaryawanHelper;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\AtasanKaryawan $model */

// Pastikan $model ada dan memiliki id_karyawan
$id_karyawan = Yii::$app->request->get('id_karyawan') ?? ($model->id_karyawan ?? null);

if (!$id_karyawan) {
    throw new \yii\web\NotFoundHttpException('ID karyawan tidak valid');
}

$karyawan = KaryawanHelper::getKaryawanById($id_karyawan);


if (!$karyawan) {
    throw new \yii\web\NotFoundHttpException('Karyawan tidak ditemukan');
}

$this->title = Yii::t('app', 'Set Atasan : {name}', [
    'name' => $karyawan[0]['nama'] ?? 'Nama Tidak Diketahui'
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Atasan Karyawan'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="atasan-karyawan-create">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'dataKaryawan' => $dataKaryawan,
        'atasanData' => $atasanData
    ]) ?>

</div>