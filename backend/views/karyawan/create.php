<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Karyawan $model */

$this->title = 'Tambah Karyawan';
$this->params['breadcrumbs'][] = ['label' => 'karyawan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="karyawan-create">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'nextKode' => $nextKode

    ]) ?>

</div>