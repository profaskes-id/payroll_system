<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\DataKeluarga $model */

$this->title = 'Tambah Data Keluarga';
$this->params['breadcrumbs'][] = ['label' => 'Data keluarga', 'url' => ['karyawan/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="data-keluarga-create">

    <div class="costume-container">
        <p class="">
            <?php
            $id_karyawan = Yii::$app->request->get('id_karyawan');
            ?>
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['karyawan/view?id_karyawan=' . $id_karyawan], ['class' => 'costume-btn']) ?>
        </p>

    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>