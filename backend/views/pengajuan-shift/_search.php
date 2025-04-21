<?php

use backend\models\Karyawan;
use backend\models\MasterKode;
use backend\models\ShiftKerja;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanShiftSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pengajuan-shift-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>


    <div class="row">


        <div class="col-md-3 ">
            <?php
            $data = \yii\helpers\ArrayHelper::map(Karyawan::find()->asArray()->where(['is_aktif' => 1])->all(), 'id_shift_kerja', 'nama');
            echo $form->field($model, 'id_karyawan')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Karyawan ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Karyawan');
            ?>
        </div>
        <div class="col-md-3 ">
            <?php
            $data = \yii\helpers\ArrayHelper::map(ShiftKerja::find()->asArray()->all(), 'id_shift_kerja', 'nama_shift');
            echo $form->field($model, 'id_shift_kerja')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Shift kerja ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Shift Kerja');
            ?>
        </div>

        <div class="col-md-3 ">

            <?= $form->field($model, 'diajukan_pada')->input('date') ?>
        </div>
        <div class="col-md-3 ">
            <?php $nama_kode = \yii\helpers\ArrayHelper::map(MasterKode::find()->where(['nama_group' => Yii::$app->params['status-pengajuan']])->all(), 'kode', 'nama_kode');
            echo $form->field($model, 'status')->widget(\kartik\select2\Select2::classname(), [
                'data' => $nama_kode,
                'language' => 'id',
                'options' => ['placeholder' => 'Cari Status pengajuan ...'],
                'pluginOptions' => [
                    'tags' => true,
                    'allowClear' => true
                ],
            ])->label("Status Pengajuan");
            ?>
        </div>

        <div class="col-2">
            <div class="items-center form-group d-flex w-100 justify-content-around">
                <button class="add-button" type="submit" data-toggle="collapse" data-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample">
                    <i class="fas fa-search"></i>
                    <span>
                        Search
                    </span>
                </button>

                <a class="reset-button" href="<?= \yii\helpers\Url::to(['index']) ?>">
                    <i class="fas fa-undo"></i>
                    <span>
                        Reset
                    </span>
                </a>
            </div>
        </div>

    </div>


    <?php ActiveForm::end(); ?>

</div>