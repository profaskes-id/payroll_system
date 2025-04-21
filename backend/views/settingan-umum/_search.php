<?php

use backend\models\SettinganUmum;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\SettinganUmumSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="settingan-umum-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>


    <div class="row">

        <div class="col-md-5">

            <?php $nama_kode = \yii\helpers\ArrayHelper::map(SettinganUmum::find()->all(), 'id_settingan_umum', 'nama_setting');
            echo $form->field($model, 'id_settingan_umum')->widget(\kartik\select2\Select2::classname(), [
                'data' => $nama_kode,
                'language' => 'id',
                'options' => ['placeholder' => 'Cari Nama Settingan ...'],
                'pluginOptions' => [
                    'tags' => true,
                    'allowClear' => true
                ],
            ])->label(false);
            ?>
        </div>

        <div class="col-md-4">
            <?php
            $statusOptions = [
                0 => 'Tidak Aktif',
                1 => 'Aktif',
            ];

            echo $form->field($model, 'nilai_setting')->widget(\kartik\select2\Select2::classname(), [
                'data' => $statusOptions,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Status ...'],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ])->label(false);
            ?>

        </div>


        <div class="col-3">
            <div class="items-center form-group d-flex w-100 justify-content-around">
                <button class="add-button" type="submit" data-toggle="collapse" data-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample">
                    <i class="fas fa-search"></i>
                    <span>
                        Search
                    </span>
                </button>

                <a class="reset-button" href="<?= \yii\helpers\Url::to(['index']) ?>">
                    <i class="fas fa-undo"></i>
                    <span>
                        Reset
                    </span>
                </a>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>