<?php

use backend\models\helpers\KaryawanHelper;
use backend\models\MasterCuti;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\JatahCutiKaryawan $model */
/** @var yii\widgets\ActiveForm $form */


$model->id_karyawan = Yii::$app->request->get('id_karyawan');
?>

<div class="table-container jatah-cuti-karyawan-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'id_karyawan')->hiddenInput(['value' => $model->id_karyawan, 'maxlength' => true])->label(false) ?>
    <div class="row">

        <div class="col-md-6 col-12">
            <?php $nama_group_cuti = \yii\helpers\ArrayHelper::map(MasterCuti::find()->asArray()->all(), 'id_master_cuti', 'jenis_cuti');
            echo $form->field($model, 'id_master_cuti')->widget(kartik\select2\Select2::classname(), [
                'data' => $nama_group_cuti,
                'language' => 'id',
                'options' => ['placeholder' => 'Cari Jenis Cuti ...'],
                'pluginOptions' => [
                    'tags' => true,
                    'allowClear' => true
                ],
            ])->label('Jenis Cuti');
            ?>
        </div>
        <div class="col-md-6 col-12">
            <?= $form->field($model, 'tahun')->textInput(['type' => 'number', 'maxlength' => true, 'value' => $tahun ?? date('Y')]) ?>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'jatah_hari_cuti')->textInput() ?>
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