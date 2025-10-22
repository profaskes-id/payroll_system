<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanTugasLuar $model */

$this->title = Yii::t('app', ' Pengajuan Tugas Luar');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pengajuan Tugas Luar'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengajuan-tugas-luar-create">
    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>