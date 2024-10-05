<?php

use backend\models\MasterKode;
use kartik\select2\Select2;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanCuti $model */

$this->title = 'Pengajuan Cuti: ' . $model->karyawan->nama;
$this->params['breadcrumbs'][] = ['label' => 'Pengajuan Cuti', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->karyawan->nama, 'url' => ['view', 'id_pengajuan_cuti' => $model->id_pengajuan_cuti]];
$this->params['breadcrumbs'][] = 'Tanggapan';
?>
<div class="pengajuan-cuti-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container table-responsive">

        <?php $form = ActiveForm::begin(); ?>


        <?= $form->field($model, 'id_karyawan')->hiddenInput(['style' => 'display:none'])->label(false) ?>
        <?= $form->field($model, 'alasan_cuti')->hiddenInput(['style' => 'display:none'])->label(false) ?>

        <div class="row ">
            <div class="col-6 w-100">
                <h3> <?= $model->karyawan->nama ?></h3>
                <p><?= $model->alasan_cuti ?></p>
            </div>
            <div class="col-6 w-100  d-flex flex-column">
                <h6 class="capitalize fw-bold " style="padding-left: 40px;"><?= $model->jenisCuti->jenis_cuti ?></h6>
                <div class="w-100  d-flex justify-content-around">
                    <p>Mulai : <?= $model->tanggal_mulai ?></p>
                    <p>s/d</p>
                    <p>selesai : <?= $model->tanggal_selesai ?></p>
                </div>
            </div>

        </div>

        <hr>

        <div class="row">
            <h5 class="col-12 pb-3">Tanggapan Admin</h5>
            <div class="col-6">
                <?= $form->field($model, 'tanggal_mulai')->textInput(['type' => 'date']) ?>
            </div>

            <div class="col-6">
                <?= $form->field($model, 'tanggal_selesai')->textInput(['type' => 'date']) ?>
            </div>
            <div class="col-6">
                <?php
                $data = \yii\helpers\ArrayHelper::map(MasterKode::find()->where(['nama_group' => Yii::$app->params['status-pengajuan']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all(), 'kode', 'nama_kode');
                echo $form->field($model, 'status')->radioList($data, [
                    'item' => function ($index, $label, $name, $checked, $value) {
                        return Html::radio($name, $checked, [
                            'value' => $value,
                            'label' => $label,
                            'labelOptions' => ['class' => 'radio-label mr-5'],
                        ]);
                    },
                ])->label('Status Pengajuan');
                ?>
            </div>


            <?php if (!$model->isNewRecord): ?>
                <div class="col-6">
                    <?= $form->field($model, 'catatan_admin')->textarea(['rows' => 1]) ?>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <button class="add-button" type="submit">
                    <span>
                        Submit
                    </span>
                </button>
            </div>




            <?php ActiveForm::end(); ?>
        </div>

    </div>