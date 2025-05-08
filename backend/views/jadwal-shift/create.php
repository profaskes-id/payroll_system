<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\JadwalShift $model */

$this->title = Yii::t('app', 'Create Jadwal Shift');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Jadwal Shifts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jadwal-shift-create">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>