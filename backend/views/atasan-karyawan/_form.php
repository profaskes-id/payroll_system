<?php

use backend\models\Karyawan;
use backend\models\MasterLokasi;
use kartik\select2\Select2;
use yii\helpers\Html;

use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\AtasanKaryawan $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php

$dataKaryawan = Karyawan::find()->select(['id_karyawan', 'nama', 'is_atasan'])->where(['is_atasan' => 1])->asArray()->all();
// dd($dataKaryawan);

?>


<div class="atasan-karyawan-form table-container">


    <?php
    $id_karyawan = Yii::$app->request->get('id_karyawan');
    ?>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_karyawan')->hiddenInput(['value' => $id_karyawan ?? $model->id_karyawan])->label(false) ?>

    <div class="row">
        <div class="col-12">
            <?php
            $data = \yii\helpers\ArrayHelper::map(
                $dataKaryawan,
                'id_karyawan',
                'nama'
            );

            echo $form->field($model, 'id_atasan')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Atasan ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Atasan Karyawan');
            ?>
        </div>
        <div class="col-12">
            <?php
            $data = \yii\helpers\ArrayHelper::map(MasterLokasi::find()->all(), 'id_master_lokasi', 'label');
            echo $form->field($model, 'id_master_lokasi')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Lokasi Penempatan ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Lokasi Penempatan');
            ?>
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