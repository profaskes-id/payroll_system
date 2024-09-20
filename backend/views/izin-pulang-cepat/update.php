<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\IzinPulangCepat $model */

$this->title = Yii::t('app', 'Tanggapan Izin Pulang Cepat: ');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Izin Pulang Cepat'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_izin_pulang_cepat, 'url' => ['view', 'id_izin_pulang_cepat' => $model->id_izin_pulang_cepat]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="izin-pulang-cepat-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>

        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>

    </div>
</div>