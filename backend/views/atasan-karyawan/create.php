<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\AtasanKaryawan $model */

$this->title = Yii::t('app', 'Tambah Atasan Karyawan');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Atasan Karyawan'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="atasan-karyawan-create">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>