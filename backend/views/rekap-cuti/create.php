<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\RekapCuti $model */

$this->title = 'Create Rekap Cuti';
$this->params['breadcrumbs'][] = ['label' => 'Rekap Cuti', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rekap-cuti-create">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>