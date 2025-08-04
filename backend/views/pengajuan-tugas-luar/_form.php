<?php

use backend\models\helpers\KaryawanHelper;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanTugasLuar $model */
/** @var backend\models\DetailTugasLuar[] $detailModels */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pengajuan-tugas-luar-form table-container">
    <div class="card">
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <div class="col-md-6">
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

                <div class="col-md-6">
                    <?= $form->field($model, 'tanggal')->input('date', [
                        'class' => 'form-control'
                    ])->label('Diajukan Untuk Tanggal') ?>
                </div>
            </div>

            <?php if (!$model->isNewRecord): ?>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'status_pengajuan')->radioList(
                            [
                                0 => 'Pending',
                                1 => 'Approve'
                            ],
                            [
                                'item' => function ($index, $label, $name, $checked, $value) {
                                    $id = 'status_pengajuan_' . $index;
                                    $checked = $checked ? 'checked' : '';

                                    return "
                                    <div class='form-check form-check-inline'>
                                        <input class='form-check-input' type='radio' name='{$name}' id='{$id}' value='{$value}' {$checked}>
                                        <label class='form-check-label' for='{$id}'>
                                            {$label}
                                        </label>
                                    </div>
                                ";
                                }
                            ]
                        )->label('Status Pengajuan') ?>
                    </div>

                    <div class="col-md-6">
                        <?= $form->field($model, 'catatan_approver')->textarea([
                            'rows' => 2,
                            'placeholder' => 'Masukkan catatan persetujuan...'
                        ]) ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Detail Tugas Luar Section -->
            <div class="mt-3">
                <h5 class="pb-1 border-bottom">Detail Tugas Luar</h5>
                <div id="detail-container">
                    <?php if (!empty($detailModels)): ?>
                        <?php foreach ($detailModels as $index => $detailModel): ?>
                            <div class="mb-2 detail-item card">
                                <div class="p-2 card-body">
                                    <div class="row g-1">
                                        <div class="col-md-6">
                                            <?= $form->field($detailModel, "[$index]keterangan", [
                                                'options' => ['class' => 'mb-1']
                                            ])->textInput([
                                                'placeholder' => 'Contoh: Ke Jakarta untuk meeting',
                                                'class' => 'form-control form-control-sm'
                                            ])->label(false) ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form->field($detailModel, "[$index]jam_diajukan", [
                                                'options' => ['class' => 'mb-1']
                                            ])->input('time', [
                                                'class' => 'form-control form-control-sm'
                                            ])->label(false) ?>
                                        </div>
                                    </div>

                                    <div class="mt-1 row g-1">
                                        <div class="col-md-6">
                                            <div class="mb-1 form-group">
                                                <small class="text-muted">Status Detail</small>
                                                <div>
                                                    <?= Html::radioList(
                                                        "DetailTugasLuar[$index][status_pengajuan_detail]",
                                                        $detailModel->status_pengajuan_detail ?? 1,  // Default value 1
                                                        [
                                                            1 => 'Disetujui',
                                                            2 => 'Ditolak'
                                                        ],
                                                        [
                                                            'item' => function ($index, $label, $name, $checked, $value) {
                                                                $id = 'status_pengajuan_detail_' . $index . '_' . $value;
                                                                $checked = $checked ? 'checked' : '';
                                                                return "
            <div class='form-check form-check-inline'>
                <input class='form-check-input' type='radio' name='{$name}' id='{$id}' value='{$value}' {$checked}>
                <label class='form-check-label' for='{$id}'>
                    {$label}
                </label>
            </div>";
                                                            }
                                                        ]
                                                    ) ?>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="mt-1 d-flex justify-content-end">
                                        <button type="button" class="btn btn-danger btn-xs remove-detail" data-index="<?= $index ?>">
                                            <i class="fas fa-trash fa-xs"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <button type="button" id="add-detail" class="mt-1 btn btn-primary btn-xs">
                    <i class="fas fa-plus fa-xs"></i> Tambah Detail
                </button>
            </div>

            <div class="mt-5 form-group">
                <button class="add-button" type="submit">
                    <span>
                        Save
                    </span>
                </button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
$js = <<<JS
// Add new detail item
$('#add-detail').click(function() {
    var index = $('.detail-item').length;
    var uniqueId = Date.now() + index; // Membuat ID unik
    
    var html = `
        <div class="mb-2 detail-item card" data-index="\${index}">
            <div class="p-2 card-body">
                <div class="row g-1">
                    <div class="col-md-6">
                        <div class="mb-1 form-group">
                            <input type="text" id="detailtugasluar-\${uniqueId}-keterangan" 
                                class="form-control form-control-sm" 
                                name="DetailTugasLuar[\${index}][keterangan]" 
                                placeholder="Contoh: Ke Jakarta untuk meeting">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-1 form-group">
                            <input type="time" id="detailtugasluar-\${uniqueId}-jam_diajukan" 
                                class="form-control form-control-sm" 
                                name="DetailTugasLuar[\${index}][jam_diajukan]">
                        </div>
                    </div>
                </div>

                <div class="mt-1 row g-1">
                    <div class="col-md-6">
                        <div class="mb-1 form-group">
                            <small class="text-muted">Status Detail</small>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" 
                                        name="DetailTugasLuar[\${index}][status_pengajuan_detail]" 
                                        id="detailtugasluar-\${uniqueId}-status_pengajuan_detail1" 
                                        value="1" checked>
                                    <label class="form-check-label" for="detailtugasluar-\${uniqueId}-status_pengajuan_detail1">Disetujui</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" 
                                        name="DetailTugasLuar[\${index}][status_pengajuan_detail]" 
                                        id="detailtugasluar-\${uniqueId}-status_pengajuan_detail2" 
                                        value="2">
                                    <label class="form-check-label" for="detailtugasluar-\${uniqueId}-status_pengajuan_detail2">Ditolak</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-1 d-flex justify-content-end">
                    <button type="button" class="btn btn-danger btn-xs remove-detail" data-index="\${index}">
                        <i class="fas fa-trash fa-xs"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    `;
    $('#detail-container').append(html);
});

// Remove detail item
$(document).on('click', '.remove-detail', function() {
    if (confirm('Apakah Anda yakin ingin menghapus detail ini?')) {
        var index = $(this).data('index');
        $('.detail-item[data-index="' + index + '"]').remove();
        
        // Reindex remaining items
        $('.detail-item').each(function(newIndex) {
            $(this).attr('data-index', newIndex);
            $(this).find('[name*="DetailTugasLuar"]').each(function() {
                var name = $(this).attr('name').replace(/\[\d+\]/, '[' + newIndex + ']');
                $(this).attr('name', name);
            });
        });
    }
});
JS;

$this->registerJs($js);
?>