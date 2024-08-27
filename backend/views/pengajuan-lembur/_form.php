<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanLembur $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pengajuan-lembur-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?php
            $data = \yii\helpers\ArrayHelper::map(\backend\models\Karyawan::find()->all(), 'id_karyawan', 'nama');
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




        <div class="col-md-6">
            <?= $form->field($model, 'tanggal')->textInput(['type' => 'date']) ?>
        </div>


        <div class="col-md-6">
            <?= $form->field($model, 'jam_mulai')->textInput(['type' => 'time']) ?>
        </div>


        <div class="col-md-6">
            <?= $form->field($model, 'jam_selesai')->textInput(['type' => 'time']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'pekerjaan')->textarea(['rows' => 1]) ?>
        </div>

        <div class="col-md-6">
            <?php
            $data = \yii\helpers\ArrayHelper::map(\backend\models\MasterKode::find()->where(['nama_group' => Yii::$app->params['status-pengajuan']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all(), 'kode', 'nama_kode');
            echo $form->field($model, 'status')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Pengajuan ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Statu Pengajuan');
            ?>
        </div>


        <div class="col-md-6">

            <div class="form-group">
                <button class="add-button" type="submit">
                    <span>
                        Submit
                    </span>
                </button>
            </div>

        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>