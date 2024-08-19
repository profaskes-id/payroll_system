<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanCuti $model */

$this->title = 'Update Pengajuan Cuti: ' . $model->id_pengajuan_cuti;
$this->params['breadcrumbs'][] = ['label' => 'Pengajuan Cutis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_pengajuan_cuti, 'url' => ['view', 'id_pengajuan_cuti' => $model->id_pengajuan_cuti]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pengajuan-cuti-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
