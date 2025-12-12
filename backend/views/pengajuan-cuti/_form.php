<?php

use backend\models\helpers\KaryawanHelper;
use backend\models\Karyawan;
use backend\models\MasterCuti;
use backend\models\MasterKode;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanCuti $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pengajuan-cuti-form table-container">

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
            <?php
            $data = \yii\helpers\ArrayHelper::map(MasterCuti::find()->all(), 'id_master_cuti', 'jenis_cuti');
            echo $form->field($model, 'jenis_cuti')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih jenis Cuti ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Jenis Cuti');
            ?>
        </div>


        <div class="col-12">

            <div id="detail-container">
                <?php if (!empty($detailModels)): ?>
                    <?php foreach ($detailModels as $index => $detailModel): ?>
                        <div class="mb-2 detail-item" data-index="<?= $index ?>">
                            <?= $form->field($detailModel, "[$index]tanggal")->input('date', ['class' => 'form-control form-control-sm'])->label('Tanggal') ?>

                            <button type="button" class="mt-1 btn btn-danger btn-sm remove-detail" data-index="<?= $index ?>">Hapus</button>
                            <hr>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <button type="button" id="add-detail" class="btn btn-primary btn-sm">Tambah Detail</button>
        <div class="col-12">
            <?= $form->field($model, 'alasan_cuti')->textarea(['rows' => 2, 'placeholder' => 'Alasan Cuti Karyawan']) ?>
        </div>



        <?php if (!$model->isNewRecord): ?>
            <div class="col-12">
                <?= $form->field($model, 'catatan_admin')->textarea(['rows' => 2]) ?>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <button class="add-button" type="submit">
                <span>
                    Submit
                </span>
            </button>
        </div>

    </div>


    <?php ActiveForm::end(); ?>

</div>



<?php
$js = <<<JS
$('#add-detail').click(function() {
    let index = $('.detail-item').length;
    let html = `
    <div class="mb-2 detail-item" data-index="\${index}">
        <label for="detailcuti-\${index}-tanggal">Tanggal</label>
        <input type="date" id="detailcuti-\${index}-tanggal" name="DetailCuti[\${index}][tanggal]" class="form-control form-control-sm" />

        

        <button type="button" class="mt-1 btn btn-danger btn-sm remove-detail" data-index="\${index}">Hapus</button>
        <hr>
    </div>
    `;
    $('#detail-container').append(html);
});

$(document).on('click', '.remove-detail', function() {
    if(confirm('Yakin ingin menghapus detail ini?')) {
        let index = $(this).data('index');
        $('.detail-item[data-index="' + index + '"]').remove();

        // Reindex items
        $('.detail-item').each(function(i) {
            $(this).attr('data-index', i);
            $(this).find('input, label').each(function() {
                let attrFor = $(this).attr('for');
                let name = $(this).attr('name');
                if(attrFor) {
                    let newFor = attrFor.replace(/\d+/, i);
                    $(this).attr('for', newFor);
                }
                if(name) {
                    let newName = name.replace(/\[\d+\]/, '[' + i + ']');
                    $(this).attr('name', newName);
                }
                if($(this).attr('id')) {
                    let newId = $(this).attr('id').replace(/\d+/, i);
                    $(this).attr('id', newId);
                }
            });
        });
    }
});
JS;
$this->registerJs($js);
?>