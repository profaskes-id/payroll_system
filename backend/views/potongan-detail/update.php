<?php

use backend\models\helpers\KaryawanHelper;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\PotonganDetail $model */


$id_karyawan = Yii::$app->request->get('id_karyawan');
$nama = KaryawanHelper::getKaryawanById($id_karyawan)[0]['nama'];


$this->title = Yii::t('app', 'Update Potongan {nama} ', [
    'nama' => $nama,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Potongan Detail'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->potongan->nama_potongan, 'url' => ['view', 'id_potongan_detail' => $model->id_potongan_detail]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="potongan-detail-update">

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