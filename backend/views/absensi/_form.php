<?php

use backend\models\helpers\KaryawanHelper;
use backend\models\MasterKode;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Absensi $model */
/** @var yii\widgets\ActiveForm $form */
$pathInfo = Yii::$app->request->getPathInfo();

?>

<div class="absensi-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">


        <?php $id_karyawan = Yii::$app->request->get('id_karyawan')  ?? $model->id_karyawan ?>
        <?php $karyawan =  KaryawanHelper::getKaryawanById($id_karyawan);
        $this->title =  "Absensi - " . $karyawan[0]['nama'];
        ?>
        <?php $tanggal = Yii::$app->request->get('tanggal'); ?>
        <?= $form->field($model, 'id_karyawan')->hiddenInput(['value' => $id_karyawan ?? $model->id_karyawan])->label(false) ?>
        <?= $form->field($model, 'tanggal')->hiddenInput(['value' => $tanggal])->label(false) ?>


        <div class="col-md-6">
            <?= $form->field($model, 'jam_masuk')->textInput(['type' => 'time', 'value' => '08:00']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'jam_pulang')->textInput(['type' => 'time', 'value' => '17:00']) ?>
        </div>




        <style>
            .radio-group .btn {
                margin-right: 10px;
                /* Spasi antar radio button */
            }

            .radio-group .btn span {
                margin-left: 5px;
                /* Spasi antara radio dan teks */
            }
        </style>
        <div class="col-md-6 col-12" style="overflow-x: auto;">
            <?php
            $data = \yii\helpers\ArrayHelper::map(
                MasterKode::find()
                    ->where(['nama_group' => Yii::$app->params['status-hadir']])
                    ->andWhere(['!=', 'status', 0])
                    ->orderBy(['urutan' => SORT_ASC])
                    ->all(),
                'kode',
                'nama_kode'
            );
            ?>
            <div class="form-group">
                <?= Html::activeLabel($model, 'kode_status_hadir', ['class' => 'control-label mb-2']) ?>
                <div class="radio-group">
                    <?= $form->field($model, 'kode_status_hadir')->radioList(
                        $data,
                        [
                            'class' => 'btn-group',
                            'data-toggle' => 'buttons',
                            'item' => function ($index, $label, $name, $checked, $value) {
                                return '<label class="btn btn-default">' .
                                    Html::radio($name, $checked, [
                                        'value' => $value,
                                        'autocomplete' => 'off',
                                        'class' => 'btn-check'
                                    ]) .
                                    '<span>' . $label . '</span>' .
                                    '</label>';
                            }
                        ]
                    )->label(false) ?>
                </div>
            </div>
        </div>


        <div class="col-md-6 col-12">
            <?= $form->field($model, 'lampiran')->textInput(["placeholder" => "Lampiran", "class" => "form-control", 'type' => 'file'])->label('Lampiran (Optional)') ?>
            <p style="margin-top: -15px; font-size: 14.5px;" class="text-capitalize  text-muted"> lampiran Jika berhalangan hadir</p>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'keterangan')->textarea(['rows' => 1, 'maxlength' => true, "class" => "form-control", "placeholder" => "Keterangan "]) ?>
        </div>



        <div class="col-md-2">
            <div class="form-group">
                <?= Html::activeLabel($model, 'is_terlambat', ['class' => 'control-label']) ?>
                <div>
                    <?= $form->field($model, 'is_terlambat')->radioList(
                        [0 => 'Tidak', 1 => 'Ya'],
                        [
                            'class' => 'btn-group',
                            'data-toggle' => 'buttons',
                            'item' => function ($index, $label, $name, $checked, $value) {
                                return '<label class="btn btn-default' . ($checked ? ' active' : '') . '">' .
                                    Html::radio($name, $checked, [
                                        'value' => $value,
                                        'autocomplete' => 'off'
                                    ]) . $label .
                                    '</label>';
                            }
                        ]
                    )->label(false) ?>
                </div>
            </div>
        </div>


        <div class="col-6">
            <?= $form->field($model, 'alasan_terlambat')->textarea(['rows' => 1, 'maxlength' => true, "class" => "form-control", "placeholder" => "Alasan Terlambat "]) ?>
        </div>
        <div class="col-4">
            <?= $form->field($model, 'lama_terlambat')->textInput(['type' => 'time',  'maxlength' => true, "class" => "form-control",]) ?>
        </div>


        <div class="col-md-2">
            <div class="form-group">
                <?= Html::activeLabel($model, 'is_lembur', ['class' => 'control-label']) ?>
                <div>
                    <?= $form->field($model, 'is_lembur')->radioList(
                        [0 => 'Tidak', 1 => 'Ya'],
                        [
                            'class' => 'btn-group',
                            'data-toggle' => 'buttons',
                            'item' => function ($index, $label, $name, $checked, $value) {
                                return '<label class="btn btn-default' . ($checked ? ' active' : '') . '">' .
                                    Html::radio($name, $checked, [
                                        'value' => $value,
                                        'autocomplete' => 'off'
                                    ]) . $label .
                                    '</label>';
                            }
                        ]
                    )->label(false) ?>
                </div>
            </div>
        </div>





        <div class="col-md-2">
            <div class="form-group">
                <?= Html::activeLabel($model, 'is_wfh', ['class' => 'control-label']) ?>
                <div>
                    <?= $form->field($model, 'is_wfh')->radioList(
                        [0 => 'Tidak', 1 => 'Ya'],
                        [
                            'class' => 'btn-group',
                            'data-toggle' => 'buttons',
                            'item' => function ($index, $label, $name, $checked, $value) {
                                return '<label class="btn btn-default' . ($checked ? ' active' : '') . '">' .
                                    Html::radio($name, $checked, [
                                        'value' => $value,
                                        'autocomplete' => 'off'
                                    ]) . $label .
                                    '</label>';
                            }
                        ]
                    )->label(false) ?>
                </div>
            </div>
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