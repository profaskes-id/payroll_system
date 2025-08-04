<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanTugasLuarSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pengajuan-tugas-luar-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'id_karyawan') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'status_pengajuan') ?>
        </div>
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

        <?php ActiveForm::end(); ?>

    </div>