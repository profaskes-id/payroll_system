<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Karyawan $model */

$this->title = 'Update Karyawan: ' . $model->id_karyawan;
$this->params['breadcrumbs'][] = ['label' => 'Karyawans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_karyawan, 'url' => ['view', 'id_karyawan' => $model->id_karyawan]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="karyawan-update">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>