<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Bagian $model */

$this->title = 'Update Bagian: ' . $model->nama_bagian;
$this->params['breadcrumbs'][] = ['label' => 'bagian', 'url' => ['/perusahaan/view', 'id_perusahaan' => $model->perusahaan->id_perusahaan]];
$this->params['breadcrumbs'][] = ['label' => $model->nama_bagian, 'url' => ['view', 'id_bagian' => $model->id_bagian]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bagian-update">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['/perusahaan/view', 'id_perusahaan' => $model->perusahaan->id_perusahaan], ['class' => 'costume-btn']) ?>
        </p>
    </div>


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>