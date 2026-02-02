<?php

use backend\models\helpers\KaryawanHelper;
use backend\models\Karyawan;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\KaryawanSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="karyawan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-md-3 col-12">
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

        <div class="col-md-3">
            <?= $form->field($model, 'tgl_mulai')
                ->input('date')
                ->label(false) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'tgl_selesai')
                ->input('date')
                ->label(false) ?>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tanggalAwalInput = document.getElementById('dynamicmodel-tgl_mulai');
        const tanggalAkhirInput = document.getElementById('dynamicmodel-tgl_selesai');

        function calculateEndDate() {
            if (!tanggalAwalInput.value) return;

            const startDate = new Date(tanggalAwalInput.value);
            const endDate = new Date(startDate);

            endDate.setMonth(endDate.getMonth() + 1);
            endDate.setDate(endDate.getDate() - 1);

            tanggalAkhirInput.value = endDate.toISOString().split('T')[0];
        }

        // hanya auto isi saat tgl_mulai berubah
        tanggalAwalInput.addEventListener('change', calculateEndDate);
    });
</script>