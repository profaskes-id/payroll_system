<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\PengalamanKerja $model */

$this->title = 'Tambah Pengalaman Kerja';
$this->params['breadcrumbs'][] = ['label' => 'Pengalaman kerja', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengalaman-kerja-create">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>