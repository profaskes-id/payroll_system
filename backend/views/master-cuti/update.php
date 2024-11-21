<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\MasterCuti $model */

$this->title = 'Master Cuti : ' . $model->jenis_cuti;
$this->params['breadcrumbs'][] = ['label' => 'Master Cuti', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->jenis_cuti, 'url' => ['view', 'id_master_cuti' => $model->id_master_cuti]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="master-cuti-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>