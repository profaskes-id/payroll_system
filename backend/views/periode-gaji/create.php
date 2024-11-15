<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\PeriodeGaji $model */

$this->title = Yii::t('app', 'Tambah Periode Gaji');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Periode Gaji'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="periode-gaji-create">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>