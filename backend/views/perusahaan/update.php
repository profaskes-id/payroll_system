<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Perusahaan $model */

$this->title = 'Update  ' . $model->nama_perusahaan;;
$this->params['breadcrumbs'][] = ['label' => 'perusahaan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nama_perusahaan, 'url' => ['view', 'id_perusahaan' => $model->id_perusahaan]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="perusahaan-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>