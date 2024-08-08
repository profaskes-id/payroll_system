<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\DataPekerjaan $model */

$this->title = 'Tambah Data Pekerjaan';
$this->params['breadcrumbs'][] = ['label' => 'Data pekerjaan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="data-pekerjaan-create">

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