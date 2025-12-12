<?php

use backend\models\helpers\KaryawanHelper;
use backend\models\Karyawan;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\TotalSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="total-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-9">
            <?php
            $nama_group = \yii\helpers\ArrayHelper::map(KaryawanHelper::getKaryawanData(), 'id_karyawan', 'nama');
            echo $form->field($model, 'id_karyawan')->widget(kartik\select2\Select2::classname(), [
                'data' => $nama_group,
                'language' => 'id',
                'options' => [
                    'placeholder' => 'Cari Karyawan ...',
                    'id' => 'search-karyawan' // ID unik
                ],
                'pluginOptions' => [
                    'tags' => true,
                    'allowClear' => true
                ],
            ])->label(false);
            ?>
        </div>

        <div class="col-3">
            <div class="items-center form-group d-flex w-100 justify-content-around">
                <button class="add-button" type="submit">
                    <i class="fas fa-search"></i>
                    <span>Search</span>
                </button>

                <a class="reset-button" href="<?= \yii\helpers\Url::to(['index']) ?>">
                    <i class="fas fa-undo"></i>
                    <span>Reset</span>
                </a>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>