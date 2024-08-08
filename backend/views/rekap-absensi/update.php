<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Absensi $model */

$this->title = 'Update Absensi: ' . $model->id_absensi;
$this->params['breadcrumbs'][] = ['label' => 'Absensis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_absensi, 'url' => ['view', 'id_absensi' => $model->id_absensi]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="absensi-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
