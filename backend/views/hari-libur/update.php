<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\HariLibur $model */

$this->title = 'Update Hari Libur: ' . $model->id_hari_libur;
$this->params['breadcrumbs'][] = ['label' => 'Hari Liburs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_hari_libur, 'url' => ['view', 'id_hari_libur' => $model->id_hari_libur]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="hari-libur-update">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>