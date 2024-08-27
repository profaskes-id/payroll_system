<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\JamKerjaKaryawan $model */

$this->title = 'Tambah Jam Kerja Karyawan';
$this->params['breadcrumbs'][] = ['label' => 'Jam Kerja Karyawan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jam-kerja-karyawan-create">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>