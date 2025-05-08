<?php

use backend\models\Karyawan;
use backend\models\MasterKode;
use backend\models\ShiftKerja;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanShift $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pengajuan-shift-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">

        <div class="col-6">
            <?php
            $data = \yii\helpers\ArrayHelper::map(Karyawan::find()->asArray()->where(['is_aktif' => 1])->all(), 'id_karyawan', 'nama');
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

        <div class="col-6">
            <?php
            $data = \yii\helpers\ArrayHelper::map(ShiftKerja::find()->asArray()->all(), 'id_shift_kerja', 'nama_shift');
            echo $form->field($model, 'id_shift_kerja')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih shift ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('shift kerja');
            ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'tanggal_awal')->input('date')->label('Tanggal Awal') ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'tanggal_akhir')->input('date')->label('Tanggal Akhir') ?>
        </div>



        <div class="col-md-6">
            <?php
            $data = \yii\helpers\ArrayHelper::map(MasterKode::find()->where(['nama_group' => Yii::$app->params['status-pengajuan']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all(), 'kode', 'nama_kode');

            echo $form->field($model, 'status')->radioList($data, [
                'item' => function ($index, $label, $name, $checked, $value) use ($model) {
                    // Tentukan apakah radio button untuk value 1 harus checked
                    if ($model->isNewRecord) {
                        $isChecked = $value == 0 ? true : $checked;
                    } else {
                        $isChecked = $checked;
                    }

                    return Html::radio($name, $isChecked, [
                        'value' => $value,
                        'label' => $label,
                        'labelOptions' => ['class' => 'radio-label mr-4'],
                    ]);
                },
            ])->label('Status Pengajuan');
            ?>
        </div>


        <div class="col-md-12">

            <?= $form->field($model, 'catatan_admin')->textarea(['rows' => 3]) ?>
        </div>

        <div class="form-group">
            <button class="add-button" type="submit">
                <span>
                    Save
                </span>
            </button>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>