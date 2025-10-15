<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\TunjanganDetail $model */

$this->title = Yii::t('app', 'Tambah Tunjangan Karyawan');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tunjangan Karyawan'), 'url' => ['/tunjangan-potongan/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tunjangan-detail-create">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['/tunjangan-potongan/index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>