<?php

use backend\models\helpers\KaryawanHelper;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var backend\models\PendapatanPotonganLainnya $model */
/** @var yii\widgets\ActiveForm $form */


// Get query parameters from URL
$request = Yii::$app->request;

$idKaryawan = $request->get('id_karyawan');
$bulan = $request->get('bulan');
$tahun = $request->get('tahun');
$pendapatan = $request->get('pendapatan');
$potongan = $request->get('potongan');

// Set default values based on URL parameters
if ($idKaryawan) {
    $model->id_karyawan = $idKaryawan;
}
if ($bulan) {
    $model->bulan = $bulan;
}
if ($tahun) {
    $model->tahun = $tahun;
}
if ($potongan !== null) {
    $model->is_potongan = $potongan;
    $model->is_pendapatan = $potongan ? 0 : 1;
}
if ($pendapatan !== null) {
    $model->is_pendapatan = $pendapatan;
    $model->is_potongan = $pendapatan ? 0 : 1;
}

// Initialize arrays for multiple inputs
$jumlahItems = $model->jumlah ? (is_array($model->jumlah) ? $model->jumlah : [$model->jumlah]) : [''];
$keteranganItems = $model->keterangan ? (is_array($model->keterangan) ? $model->keterangan : [$model->keterangan]) : [''];

// If arrays don't have same length, adjust them
$maxCount = max(count($jumlahItems), count($keteranganItems));
while (count($jumlahItems) < $maxCount) $jumlahItems[] = '';
while (count($keteranganItems) < $maxCount) $keteranganItems[] = '';

// Bulan options
$bulanOptions = [
    1 => 'Januari',
    2 => 'Februari',
    3 => 'Maret',
    4 => 'April',
    5 => 'Mei',
    6 => 'Juni',
    7 => 'Juli',
    8 => 'Agustus',
    9 => 'September',
    10 => 'Oktober',
    11 => 'November',
    12 => 'Desember'
];

// Tahun options (10 tahun terakhir)
$tahunOptions = [];
$currentYear = date('Y');
for ($i = $currentYear; $i >= $currentYear - 10; $i--) {
    $tahunOptions[$i] = $i;
}
?>

<div class="table-container pendapatan-potongan-lainnya-form">
    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'labelOptions' => ['class' => 'form-label fw-semibold'],
            'inputOptions' => ['class' => 'form-control'],
            'errorOptions' => ['class' => 'invalid-feedback']
        ]
    ]); ?>
    <div class="row">
        <!-- Karyawan -->
        <div class="col-md-4">
            <?php
            $data = \yii\helpers\ArrayHelper::map(
                KaryawanHelper::getKaryawanData(),
                'id_karyawan',
                'nama'
            );

            echo $form->field($model, 'id_karyawan')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => [
                    'placeholder' => 'Pilih Karyawan ...',
                    'value' => $model->id_karyawan,
                    'class' => 'form-select',
                    'readonly' => true
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ])->label('Karyawan <span class="text-danger">*</span>');
            ?>
        </div>

        <!-- Periode Gaji -->
        <div class="col-md-8">
            <div class="row">
                <!-- Bulan -->

                <div class="mb-3 col-md-6">
                    <label for="">Bulan <span class="text-danger">*</span></label>
                    <?= $form->field($model, 'bulan')->dropDownList(
                        $bulanOptions,
                        [
                            'prompt' => 'Pilih Bulan',
                            'class' => 'form-select form-select-lg',
                            'style' => 'width: 100%',
                            'value' => $model->bulan,
                            'readonly' => true
                        ]
                    )->label(false) ?>
                </div>

                <!-- Tahun -->
                <div class="mb-3 col-md-6">
                    <label for=""> Tahun <span class="text-danger">*</span></label>
                    <?= $form->field($model, 'tahun')->dropDownList(
                        $tahunOptions,
                        [
                            'prompt' => 'Pilih Tahun',
                            'style' => 'width: 100%',
                            'class' => 'form-select form-select-lg',
                            'value' => $model->tahun ?: $currentYear,
                            'readonly' => true
                        ]
                    )->label(false) ?>
                </div>
            </div>
        </div>
    </div>


    <!-- Multiple Input for Jumlah and Keterangan -->
    <div class="mb-4 col-12">
        <div class="border-0 shadow-sm card">
            <div class="py-3 card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0 card-title fw-semibold">
                    <i class="fas fa-list me-2"></i>
                    Detail <?= $model->is_pendapatan ? 'Pendapatan' : 'Potongan' ?>
                </h5>
                <button type="button" class="btn btn-primary btn-sm" id="btn-add-row">
                    <i class="fas fa-plus me-1"></i> Tambah Item
                </button>
            </div>
            <div class="card-body">
                <div id="detail-container">
                    <?php foreach ($jumlahItems as $index => $jumlah): ?>
                        <div class="mb-3 row align-items-end detail-row" data-index="<?= $index ?>">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label fw-medium">
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-medium">
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
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label class="form-label d-block">&nbsp;</label>
                                    <?php if ($index > 0): ?>
                                        <button type="button" class="btn btn-outline-danger btn-remove-row" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-outline-secondary" disabled>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="mt-3 text-muted">
                    <small>
                        <i class="fas fa-info-circle me-1"></i>
                        Minimal isi satu item <?= $model->is_pendapatan ? 'pendapatan' : 'potongan' ?>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden fields for is_pendapatan and is_potongan -->
    <?= $form->field($model, 'is_pendapatan')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'is_potongan')->hiddenInput()->label(false) ?>

    <!-- Submit Button -->
    <div class="form-group">
        <button class="add-button" type="submit">
            <span>
                Save
            </span>
        </button>
    </div>
</div>

<?php ActiveForm::end(); ?>
</div>
<?php
// Tambahkan CSS ini di bagian registerCss
$css = <<<CSS
/* Styling untuk periode gaji */
.form-select-lg {
    padding: 0.4rem 1rem;
    font-size: 1rem;
    border-radius: 0.5rem;
    border: 1px solid #ced4da;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.card {
    border-radius: 10px;
    overflow: hidden;
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

.card-header {
    border-bottom: 2px solid rgba(0,0,0,.05);
    background-color: #f8f9fa !important;
}

.alert-info {
    background-color: #e7f1ff;
    border-color: #d1e3ff;
    color: #084298;
    border-radius: 0.5rem;
}

.badge {
    font-size: 0.875em;
    padding: 0.35em 0.65em;
    font-weight: 500;
}

.bg-success {
    background-color: #198754 !important;
}

.bg-warning {
    background-color: #ffc107 !important;
    color: #000;
}

#btn-switch-type {
    font-size: 0.85rem;
    padding: 0.25rem 0.75rem;
}

/* Styling untuk Select2 */
.select2-container--krajee .select2-selection {
    border: 1px solid #dee2e6;
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    min-height: 48px;
}

.select2-container--krajee .select2-selection--single .select2-selection__rendered {
    line-height: 1.5;
    padding-left: 0;
}

.select2-container--krajee .select2-selection--single .select2-selection__arrow {
    height: 46px;
}
CSS;

$this->registerCss($css);
?>

<?php
// JavaScript tambahan untuk update periode display dan switch type
$js = <<<JS
$(document).ready(function() {
    // Fungsi tambah row
    $('#btn-add-row').click(function() {
        var container = $('#detail-container');
        var index = container.find('.detail-row').length;

        // Buat row baru
        var newRow = $('<div>', { class: 'mb-3 row align-items-end detail-row', 'data-index': index });

        // Jumlah
        var colJumlah = $('<div>', { class: 'col-md-5' }).append(
            $('<div>', { class: 'form-group' }).append(
                $('<label>', { class: 'form-label fw-medium', html: 'Jumlah' }),
                $('<div>', { class: 'input-group' }).append(
                    $('<span>', { class: 'input-group-text', text: 'Rp' }),
                    $('<input>', {
                        type: 'number',
                        name: 'PendapatanPotonganLainnya[jumlah][]',
                        class: 'form-control jumlah-input',
                        value: '',
                        step: 1000,
                        min: 0,
                        placeholder: '0'
                    })
                )
            )
        );

        // Keterangan
        var colKeterangan = $('<div>', { class: 'col-md-6' }).append(
            $('<div>', { class: 'form-group' }).append(
                $('<label>', { class: 'form-label fw-medium', html: 'Keterangan' }),
                $('<input>', {
                    type: 'text',
                    name: 'PendapatanPotonganLainnya[keterangan][]',
                    class: 'form-control keterangan-input',
                    value: '',
                    placeholder: 'Masukkan keterangan'
                })
            )
        );

        // Tombol hapus
        var colRemove = $('<div>', { class: 'col-md-1' }).append(
            $('<div>', { class: 'form-group' }).append(
                $('<label>', { class: 'form-label d-block', html: '&nbsp;' }),
                $('<button>', {
                    type: 'button',
                    class: 'btn btn-outline-danger btn-remove-row',
                    title: 'Hapus'
                }).append($('<i>', { class: 'fas fa-trash' }))
            )
        );

        newRow.append(colJumlah, colKeterangan, colRemove);
        container.append(newRow);
    });

    // Fungsi hapus row
    $(document).on('click', '.btn-remove-row', function() {
        $(this).closest('.detail-row').remove();
    });
});

JS;

$this->registerJs($js);
?>