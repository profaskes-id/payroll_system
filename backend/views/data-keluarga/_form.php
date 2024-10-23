<?php

use backend\models\MasterKode;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\DataKeluarga $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="data-keluarga-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <?php $id_karyawan = Yii::$app->request->get('id_karyawan'); ?>

        <?= $form->field($model, 'id_karyawan')->hiddenInput(['value' => $id_karyawan ?? $model->id_karyawan])->label(false) ?>


        <div class="col-12">
            <?= $form->field($model, 'nama_anggota_keluarga')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-12">
            <?php
            $data = \yii\helpers\ArrayHelper::map(MasterKode::find()->where(['nama_group' => Yii::$app->params['hubungan-keluarga']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all(), 'kode', 'nama_kode');
            echo $form->field($model, 'hubungan')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Hubungan Keluarga ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>

        <div class="col-12">
            <?= $form->field($model, 'pekerjaan')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-12">
            <?= $form->field($model, 'tahun_lahir')->textInput(['type' => 'number']) ?>
        </div>
    </div>

    <div class="form-group">
        <button class="add-button" type="submit">
            <span>
                Save
            </span>
        </button>
    </div>
    <?php ActiveForm::end(); ?>
</div>