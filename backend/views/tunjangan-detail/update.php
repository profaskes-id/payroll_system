<?php

use backend\models\helpers\KaryawanHelper;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\TunjanganDetail $model */


$id_karyawan = Yii::$app->request->get('id_karyawan');
$nama = KaryawanHelper::getKaryawanById($id_karyawan)[0]['nama'];


$this->title = Yii::t('app', 'Update Tunjangan  {name}', [
    'name' => $nama,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tunjangan Karyawan'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->karyawan->nama, 'url' => ['view', 'id_tunjangan_detail' => $model->id_tunjangan_detail]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tunjangan-detail-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['/tunjangan-potongan/index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'id_karyawan' => $id_karyawan
    ]) ?>

</div>