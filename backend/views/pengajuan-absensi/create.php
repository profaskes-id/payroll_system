<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanAbsensi $model */

$this->title = Yii::t('app', 'Pengajuan Absensi');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pengajuan Absensis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengajuan-absensi-create">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>