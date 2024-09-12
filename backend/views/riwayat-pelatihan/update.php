<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\RiwayatPelatihan $model */

$this->title = Yii::t('app', 'Update Riwayat Pelatihan');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Riwayat Pelatihans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_riwayat_pelatihan, 'url' => ['view', 'id_riwayat_pelatihan' => $model->id_riwayat_pelatihan]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="riwayat-pelatihan-update">

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