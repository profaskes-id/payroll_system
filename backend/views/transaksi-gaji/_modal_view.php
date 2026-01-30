<?php

use yii\bootstrap4\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<!-- Load Bootstrap 5 and jQuery from CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- ============================================================================ -->
<!-- CSS STYLES -->
<!-- ============================================================================ -->

<style>
    .detail-card {
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 15px;
    }

    .detail-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #dee2e6;
    }

    .detail-row {
        display: grid;
        grid-template-columns: 1fr 2fr auto;
        gap: 15px;
        margin-bottom: 15px;
        align-items: end;
    }

    .col-jumlah,
    .col-keterangan,
    .col-action {
        min-width: 0;
    }

    .btn-remove {
        background: #dc3545;
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 4px;
        cursor: pointer;
    }

    .btn-remove:disabled {
        background: #6c757d;
        cursor: not-allowed;
        opacity: 0.5;
    }

    .btn-remove:not(:disabled):hover {
        background: #c82333;
    }
</style>

<!-- ============================================================================ -->
<!-- BUTTON SECTION -->
<!-- ============================================================================ -->



<!-- ============================================================================ -->
<!-- MODAL SECTION -->
<!-- ============================================================================ -->

<?php
Modal::begin([
    'id' => 'modal-form',
    'title' => '<h5 id="modal-title">Tambah Pendapatan/Potongan Lainnya</h5>',
    'size' => Modal::SIZE_LARGE,
    'options' => [
        'tabindex' => false
    ]
]);
?>

<!-- ============================================================================ -->
<!-- FORM SECTION -->
<!-- ============================================================================ -->

<?php $form = ActiveForm::begin([
    'id' => 'pendapatan-potongan-form',
    'fieldConfig' => [
        'template' => "{label}\n{input}\n{error}",
        'labelOptions' => ['class' => 'form-label'],
        'inputOptions' => ['class' => 'form-control'],
        'errorOptions' => ['class' => 'invalid-feedback']
    ],
    'action' => ['/pendapatan-potongan-lainnya/create']
]); ?>

<!-- Hidden fields -->
<?= $form->field($model, 'id_karyawan')->hiddenInput()->label(false) ?>
<?= $form->field($model, 'bulan')->hiddenInput()->label(false) ?>
<?= $form->field($model, 'tahun')->hiddenInput()->label(false) ?>
<?= $form->field($model, 'is_pendapatan')->hiddenInput(['id' => 'is-pendapatan'])->label(false) ?>
<?= $form->field($model, 'is_potongan')->hiddenInput(['id' => 'is-potongan'])->label(false) ?>

<!-- Dynamic Input Section -->
<div class="form-group">
    <div class="detail-card">
        <div class="detail-card-header">
            <h6>Detail <span id="jenis-label"><?= $model->is_pendapatan ? 'Pendapatan' : 'Potongan' ?></span></h6>
            <button type="button" class="btn btn-primary btn-sm btn-add-item" id="btn-add-row">
                + Tambah Item
            </button>
        </div>

        <div id="detail-container">
            <?php foreach ($jumlahItems as $index => $jumlah): ?>
                <div class="detail-row" data-index="<?= $index ?>">
                    <div class="col-jumlah">
                        <div class="form-group">
                            <label class="form-label">
                                Jumlah <?= $index === 0 ? '<span class="text-danger">*</span>' : '' ?>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number"
                                    name="PendapatanPotonganLainnya[jumlah][]"
                                    class="form-control jumlah-input"
                                    value="<?= Html::encode($jumlah) ?>"
                                    step="1000"
                                    min="0"
                                    <?= $index === 0 ? 'required' : '' ?>
                                    placeholder="0">
                            </div>
                        </div>
                    </div>
                    <div class="col-keterangan">
                        <div class="form-group">
                            <label class="form-label">
                                Keterangan <?= $index === 0 ? '<span class="text-danger">*</span>' : '' ?>
                            </label>
                            <input type="text"
                                name="PendapatanPotonganLainnya[keterangan][]"
                                class="form-control keterangan-input"
                                value="<?= Html::encode($keteranganItems[$index] ?? '') ?>"
                                <?= $index === 0 ? 'required' : '' ?>
                                placeholder="Masukkan keterangan">
                        </div>
                    </div>
                    <div class="col-action">
                        <label class="form-label d-block">&nbsp;</label>
                        <?php if ($index > 0): ?>
                            <button type="button" class="btn-remove btn-remove-row" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        <?php else: ?>
                            <button type="button" class="btn-remove" disabled>
                                <i class="fas fa-trash"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="margin-top: 10px; color: #6c757d; font-size: 13px;">
            <i class="fas fa-info-circle"></i>
            Minimal isi satu item <span id="jenis-info"><?= $model->is_pendapatan ? 'pendapatan' : 'potongan' ?></span>
        </div>
    </div>
</div>

<!-- Submit Button -->
<div class="gap-3 form-group d-flex">
    <button type="button" class="reset-button " data-bs-dismiss="modal">Batal</button>
    <?= Html::submitButton('Simpan', ['class' => 'px-5 add-button']) ?>
</div>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>



<!-- ============================================================================ -->
<!-- JAVASCRIPT -->
<!-- ============================================================================ -->

<script>
    jQuery(document).ready(function($) {
        'use strict';

        console.log('Script loaded'); // Debug

        // Event listener untuk tombol Pendapatan
        $(document).on('click', '#btn-pendapatan', function() {
            console.log('Pendapatan clicked');
            $('#is-pendapatan').val(1);
            $('#is-potongan').val(0);
            $('#jenis-label').text('Pendapatan');
            $('#jenis-info').text('pendapatan');
            $('#modal-title').text('Tambah Pendapatan Lainnya');
        });

        // Event listener untuk tombol Potongan
        $(document).on('click', '#btn-potongan', function() {
            console.log('Potongan clicked');
            $('#is-pendapatan').val(0);
            $('#is-potongan').val(1);
            $('#jenis-label').text('Potongan');
            $('#jenis-info').text('potongan');
            $('#modal-title').text('Tambah Potongan Lainnya');
        });

        // Fungsi tambah row - MENGGUNAKAN EVENT DELEGATION
        $(document).on('click', '#btn-add-row', function(e) {
            e.preventDefault();
            console.log('Add row clicked'); // Debug

            var container = $('#detail-container');
            var index = container.find('.detail-row').length;

            console.log('Current index:', index); // Debug

            // Template untuk row baru
            var newRowHtml =
                '<div class="detail-row" data-index="' + index + '">' +
                '<div class="col-jumlah">' +
                '<div class="form-group">' +
                '<label class="form-label">Jumlah</label>' +
                '<div class="input-group">' +
                '<span class="input-group-text">Rp</span>' +
                '<input type="number" name="PendapatanPotonganLainnya[jumlah][]" class="form-control jumlah-input" value="" step="1000" min="0" placeholder="0">' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="col-keterangan">' +
                '<div class="form-group">' +
                '<label class="form-label">Keterangan</label>' +
                '<input type="text" name="PendapatanPotonganLainnya[keterangan][]" class="form-control keterangan-input" value="" placeholder="Masukkan keterangan">' +
                '</div>' +
                '</div>' +
                '<div class="col-action">' +
                '<label class="form-label d-block">&nbsp;</label>' +
                '<button type="button" class="btn-remove btn-remove-row" title="Hapus">' +
                '<i class="fas fa-trash"></i>' +
                '</button>' +
                '</div>' +
                '</div>';

            container.append(newRowHtml);
            console.log('Row added'); // Debug
        });

        // Fungsi hapus row
        $(document).on('click', '.btn-remove-row', function(e) {
            e.preventDefault();
            console.log('Remove row clicked'); // Debug
            $(this).closest('.detail-row').remove();

            // Update required attribute untuk row pertama
            $('#detail-container .detail-row').each(function(index) {
                if (index === 0) {
                    $(this).find('.jumlah-input').attr('required', true);
                    $(this).find('.keterangan-input').attr('required', true);
                    $(this).find('.btn-remove').prop('disabled', true);
                } else {
                    $(this).find('.jumlah-input').removeAttr('required');
                    $(this).find('.keterangan-input').removeAttr('required');
                }
            });
        });

    });
</script>