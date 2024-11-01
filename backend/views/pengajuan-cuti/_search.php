<?php

use backend\models\helpers\KaryawanHelper;
use backend\models\Karyawan;
use backend\models\MasterCuti;
use backend\models\MasterKode;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanCutiSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pengajuan-cuti-search">


    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'POST',
    ]); ?>

    <div class="row">
        <div class="col-md-3 col-12">
            <?php $nama_group = \yii\helpers\ArrayHelper::map(KaryawanHelper::getKaryawanData(), 'id_karyawan', 'nama');
            echo $form->field($model, 'id_karyawan')->widget(Select2::classname(), [
                'data' => $nama_group,
                'language' => 'id',
                'options' => ['placeholder' => 'Cari Karyawan ...'],
                'pluginOptions' => [
                    'tags' => true,
                    'allowClear' => true
                ],
            ])->label("Karyawan");
            ?>
        </div>
        <div class="col-md-3 col-12">
            <?php $nama_kode = \yii\helpers\ArrayHelper::map(MasterCuti::find()->all(), 'id_master_cuti', 'jenis_cuti');
            echo $form->field($model, 'jenis_cuti')->widget(Select2::classname(), [
                'data' => $nama_kode,
                'language' => 'id',
                'options' => ['placeholder' => 'Cari Jenis Cuti ...'],
                'pluginOptions' => [
                    'tags' => true,
                    'allowClear' => true
                ],
            ])->label("Jenis Cuti");
            ?>
        </div>
        <div class="col-12 col-md-3">
            <?= $form->field($model, 'tanggal_mulai')->textInput(['type' => 'date', 'value' => $tgl_mulai])->label("Mulai Dari") ?>
        </div>
        <div class="col-12 col-md-3">
            <?= $form->field($model, 'tanggal_selesai')->textInput(['type' => 'date', 'value' => $tgl_selesai])->label("Sampai Dengan") ?>
        </div>

        <div class="col-3">
            <div class="form-group d-flex items-center w-100  justify-content-around">
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