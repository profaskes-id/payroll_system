<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\ShiftKerja $model */

$this->title = Yii::t('app', 'Tambah Shift Kerja');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shift Kerja'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shift-kerja-create">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>