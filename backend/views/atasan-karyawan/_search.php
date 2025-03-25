<?php

use backend\models\helpers\KaryawanHelper;
use backend\models\Karyawan;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\AtasanKaryawanSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="atasan-karyawan-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-md-5 col-12">
            <?php $nama_group_atasan = \yii\helpers\ArrayHelper::map(KaryawanHelper::getKaryawanData(), 'id_karyawan', 'nama');
            echo $form->field($model, 'id_atasan')->widget(kartik\select2\Select2::classname(), [
                'data' => $nama_group_atasan,
                'language' => 'id',
                'options' => ['placeholder' => 'Cari Atasan ...'],
                'pluginOptions' => [
                    'tags' => true,
                    'allowClear' => true
                ],
            ])->label(false);
            ?>
        </div>
        <div class="col-md-4 col-12">
            <?php $nama_group = \yii\helpers\ArrayHelper::map(KaryawanHelper::getKaryawanData(), 'id_karyawan', 'nama');
            echo $form->field($model, 'id_karyawan')->widget(kartik\select2\Select2::classname(), [
                'data' => $nama_group,
                'language' => 'id',
                'options' => ['placeholder' => 'Cari karyawan ...'],
                'pluginOptions' => [
                    'tags' => true,
                    'allowClear' => true
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