<?php

use backend\models\helpers\KaryawanHelper;
use backend\models\JamKerjaKaryawan;
use backend\models\MasterKode;
use backend\models\ShiftKerja;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$pathInfo = Yii::$app->request->getPathInfo();
?>
<style>
    .radio-group .btn {
        margin-right: 10px;
    }

    .radio-group .btn span {
        margin-left: 5px;
    }
</style>
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
            <?= $form->field($model, 'jam_masuk')->textInput([
                'type' => 'time',
                'value' => $model->isNewRecord ? '08:00' : substr($model->jam_masuk, 0, 5),
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'jam_pulang')->textInput([
                'type' => 'time',
                'value' => !empty($model->jam_pulang) ? substr($model->jam_pulang, 0, 5) : null,
            ]) ?>
        </div>
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
        <div class="col-md-3">
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
        <div class="col-md-3">
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
        <div class="col-md-6 col-12">
            <?= $form->field($model, 'lampiran')->textInput(["placeholder" => "Lampiran", "class" => "form-control", 'type' => 'file'])->label('Lampiran (Optional)') ?>
            <p style="margin-top: -15px; font-size: 14.5px;" class="text-capitalize text-muted"> lampiran Jika berhalangan hadir</p>
        </div>
        <div class="col-12 col-md-6">
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
        <div class="col-12 col-md-6">
            <?= $form->field($model, 'alasan_terlambat')->textarea(['rows' => 1, 'maxlength' => true, "class" => "form-control", "placeholder" => "Alasan Terlambat "]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'lama_terlambat')->textInput([
                'type' => 'time',
                'step' => '1',
                'lang' => 'id',
                'class' => 'form-control',
            ]) ?>
        </div>
        <?php
        $jamKerjaKaryawan = JamKerjaKaryawan::find()->where(['id_karyawan' => $id_karyawan])->one();
        ?>
        <?php if (isset($jamKerjaKaryawan) && $jamKerjaKaryawan->is_shift == 1) : ?>
            <?php
            $shiftList = ShiftKerja::find()->asArray()->all();
            $shiftOptions = [];
            foreach ($shiftList as $shift) {
                $shiftOptions[$shift['id_shift_kerja']] = $shift['nama_shift'];
            }
            ?>
            <div class="col-md-8 col-12">
                <div class="form-group">
                    <?= Html::activeLabel($model, 'id_shift', [
                        'class' => 'control-label',
                        'label' => 'Pilih Shift Kerja'
                    ]) ?>
                    <div>
                        <?= $form->field($model, 'id_shift')->widget(Select2::classname(), [
                            'data' => $shiftOptions,
                            'options' => [
                                'placeholder' => 'Pilih Shift Kerja ...',
                                'multiple' => false
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ]
                        ])->label(false) ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
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