<?php

use backend\models\MasterLokasi;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\AtasanKaryawan $model */
/** @var yii\widgets\ActiveForm $form */
?>




<div class="atasan-karyawan-form table-container">


    <?php
    $id_atasan = Yii::$app->request->get('id_atasan');

    ?>
    <?php $form = ActiveForm::begin(); ?>

    <div class="row">


        <div class="col-12">
            <?php
            $data = \yii\helpers\ArrayHelper::map(
                $dataKaryawan,
                'id_karyawan',
                'nama'
            );

            // Check if 'id_atasan' is provided
            if ($id_atasan) {
                // Set the model's 'id_atasan' to the passed 'id_atasan'
                $model->id_atasan = $id_atasan;
                echo $form->field($model, 'id_atasan')->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'id',
                    'options' => [
                        'placeholder' => 'Pilih Atasan ...',
                        'value' => $id_atasan,
                        'disabled' => true
                    ],
                    'pluginOptions' => [
                        'allowClear' => false, // Disable clear option since it's preselected
                    ],
                ])->label('Atasan Karyawan');
            } else {
                // If 'id_atasan' is not provided, render normally
                echo $form->field($model, 'id_atasan')->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'id',
                    'options' => ['placeholder' => 'Pilih Atasan ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label('Atasan Karyawan anjay');
            }
            ?>
        </div>
        <div class="col-12">
            <?php
            $data = \yii\helpers\ArrayHelper::map(
                $dataKaryawan,
                'id_karyawan',
                'nama'
            );

            echo $form->field($model, 'id_karyawan')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih karyawan ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Karyawan');
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




    <?php if (!empty($atasanData)): ?>
        <div class="table-container">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Atasan</th>
                        <th>Karyawan</th>
                        <th>Lokasi Penempatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($atasanData as $x): ?>
                        <tr>
                            <td><?= Html::encode($x['nama_atasan']); ?></td>
                            <td><?= Html::encode($x['nama_karyawan']); ?></td>
                            <td><?= Html::encode($x['id_master_lokasi']); ?></td>
                            <td class="text-center " style="width: 30px;">
                                <?= Html::a("
                                    <div class='reset-button '><i class='fa fa-trash'></i></div>
                                
                                ", ['/atasan-karyawan/delete-custom', 'id_atasan_karyawan' => $x['id_atasan_karyawan']], [
                                    'data' => [
                                        'method' => 'post',
                                        'confirm' => 'Are you sure you want to delete this item?', // Opsional: konfirmasi sebelum menghapus
                                    ],
                                ]) ?> </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>



</div>