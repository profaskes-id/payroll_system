<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanDinas $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pengajuan-dinas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_karyawan')->textInput() ?>

    <?= $form->field($model, 'keterangan_perjalanan')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'tanggal_mulai')->textInput() ?>

    <?= $form->field($model, 'tanggal_selesai')->textInput() ?>

    <?= $form->field($model, 'estimasi_biaya')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'biaya_yang_disetujui')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'disetujui_oleh')->textInput() ?>

    <?= $form->field($model, 'disetujui_pada')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
