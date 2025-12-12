<?php

use backend\models\helpers\KaryawanHelper;
use backend\models\MasterKode;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanAbsensi $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="table-container pengajuan-absensi-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-12">
            <?php
            $data = \yii\helpers\ArrayHelper::map(KaryawanHelper::getKaryawanData(), 'id_karyawan', 'nama');
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
        <div class="col-12">
            <?= $form->field($model, 'tanggal_absen')->textInput(['type' => 'date']) ?>
        </div>

        <div class="col-md-6 col-12">
            <?= $form->field($model, 'jam_masuk')->textInput(['type' => 'time']) ?>
        </div>

        <div class="col-md-6 col-12">
            <?= $form->field($model, 'jam_keluar')->textInput(['type' => 'time']) ?>
        </div>

        <div class=" col-12">
            <?= $form->field($model, 'alasan_pengajuan')->textarea(['rows' => 1]) ?>
        </div>


        <div class="col-md-6 col-12">
            <div class="form-group">
                <?= Html::activeLabel($model, 'kode_status_hadir', [
                    'class' => 'form-label mb-2'
                ]) ?>

                <?php
                // Data manual
                $data = [
                    'H'   => 'Hadir',
                    'DL'  => 'Dinas Luar',
                    'WFH' => 'WFH',
                ];
                ?>

                <div class="gap-4 d-flex align-items-center">
                    <?= $form->field($model, 'kode_status_hadir')->radioList(
                        $data,
                        [
                            'class' => 'd-flex align-items-center gap-4',
                            'item' => function ($index, $label, $name, $checked, $value) {
                                return '
                        <div class="form-check form-check-inline">
                            <input 
                                class="form-check-input"
                                type="radio"
                                name="' . $name . '"
                                value="' . $value . '"
                                id="radio-' . $value . '"
                                ' . ($checked ? 'checked' : '') . '
                            >
                            <label class="form-check-label" for="radio-' . $value . '">' . $label . '</label>
                        </div>';
                            }
                        ]
                    )->label(false) ?>
                </div>
            </div>
        </div>

        <?php if (!$model->isNewRecord): ?>




            <div class=" col-12">
                <?= $form->field($model, 'catatan_approver')->textarea(['rows' => 6]) ?>
            </div>

            <div class="col-12">

                <?php
                $data = \yii\helpers\ArrayHelper::map(MasterKode::find()->where(['nama_group' => Yii::$app->params['status-pengajuan']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all(), 'kode', 'nama_kode');


                echo $form->field($model, 'status')->radioList($data, [
                    'item' => function ($index, $label, $name, $checked, $value) use ($model) {
                        // Tentukan apakah radio button untuk value 1 harus checked

                        if ($model->isNewRecord) {
                            $isChecked = $value == 1 ? true : $checked;
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
        <?php endif; ?>







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