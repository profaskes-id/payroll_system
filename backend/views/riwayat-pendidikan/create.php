<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\RiwayatPendidikan $model */

$this->title = 'Tambah Riwayat Pendidikan';
$this->params['breadcrumbs'][] = ['label' => 'Riwayat pendidikan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="riwayat-pendidikan-create">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>