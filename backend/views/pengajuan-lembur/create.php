<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanLembur $model */

$this->title = 'Tambah Pengajuan Lembur';
$this->params['breadcrumbs'][] = ['label' => 'Pengajuan Lembur', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengajuan-lembur-create">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
        'poinArray' => $poinArray,
    ]) ?>

</div>