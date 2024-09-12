<?php

use backend\models\MasterKode;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\RiwayatPendidikan $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="riwayat-pendidikan-form table-container">

    <?php $form = ActiveForm::begin(); ?>


    <div class="row">
        <?php $id_karyawan = Yii::$app->request->get('id_karyawan'); ?>

        <?= $form->field($model, 'id_karyawan')->hiddenInput(['value' => $id_karyawan ?? $model->id_karyawan])->label(false) ?>

        <div class="col-md-6">
            <?php
            $pendidikan = \yii\helpers\ArrayHelper::map(MasterKode::find()->where(['nama_group' => Yii::$app->params['jenjang-pendidikan']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all(), 'kode', 'nama_kode');
            echo $form->field($model, 'jenjang_pendidikan')->widget(Select2::classname(), [
                'data' => $pendidikan,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Jenjang Pedidikan ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'institusi')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'tahun_masuk')->textInput() ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'tahun_keluar')->textInput() ?>
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