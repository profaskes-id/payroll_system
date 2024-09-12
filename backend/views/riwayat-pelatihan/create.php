<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\RiwayatPelatihan $model */

$this->title = Yii::t('app', 'Tambah Riwayat Pelatihan');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Riwayat Pelatihans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="riwayat-pelatihan-create">

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