<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\DataPekerjaan $model */

$this->title = 'Update Data Pekerjaan: ' . $model->id_data_pekerjaan;
$this->params['breadcrumbs'][] = ['label' => 'Data Pekerjaans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_data_pekerjaan, 'url' => ['view', 'id_data_pekerjaan' => $model->id_data_pekerjaan]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="data-pekerjaan-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>