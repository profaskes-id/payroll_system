<?php

use backend\models\MasterKode;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Perusahaan $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="perusahaan-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'nama_perusahaan')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-6">
            <?php
            $pendidikan = \yii\helpers\ArrayHelper::map(MasterKode::find()->where(['nama_group' => Yii::$app->params['status-perusahaan']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all(), 'kode', 'nama_kode');
            echo $form->field($model, 'status_perusahaan')->widget(Select2::classname(), [
                'data' => $pendidikan,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Status Perusahaan ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'direktur')->textInput(['maxlength' => true])->label('Nama Direktur') ?>
        </div>
        <div class="col-md-6 ">
            <?= $form->field($model, 'logo')->fileInput(['maxlength' => true, 'class' => 'form-control'])->label('Logo') ?>
        </div>
        <div class="col-md-6 ">
            <?= $form->field($model, 'alamat')->textarea(['maxlength' => true, 'row' => 5])->label('Alamat') ?>
        </div>

        <div class="col-md-6 ">
            <?= $form->field($model, 'bidang_perusahaan')->textarea(['maxlength' => true, 'row' => 5, 'class' => 'form-control'])->label('Bidang Usaha') ?>
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