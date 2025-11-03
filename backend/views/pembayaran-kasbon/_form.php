<?php

use backend\models\PeriodeGaji;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="pembayaran-kasbon-form table-container">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 card-title">Form Pembayaran Kasbon</h5>
                </div>
                <div class="card-body">
                    <?= $form->field($model, 'id_karyawan')->hiddenInput()->label(false) ?>
                    <?= $form->field($model, 'id_kasbon')->hiddenInput()->label(false) ?>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <?php

                            // Daftar bulan
                            $bulanList = [
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

                            // Input Select2 untuk Bulan
                            echo $form->field($model, 'bulan')->widget(Select2::classname(), [
                                'data' => $bulanList,
                                'options' => ['placeholder' => 'Pilih bulan ...'],
                                'pluginOptions' => ['allowClear' => true],
                            ])->label('Bulan');
                            ?>
                        </div>

                        <div class="col-12 col-md-6">
                            <?php
                            // Input Number untuk Tahun (default tahun sekarang)
                            echo $form->field($model, 'tahun')->input('number', [
                                'value' => date('Y'),
                                'min' => 2000,
                                'max' => date('Y') + 1,
                            ])->label('Tahun');
                            ?>
                        </div>
                    </div>


                    <div class="mb-3">
                        <?= $form->field($model, 'jumlah_potong')->textInput([
                            'maxlength' => true,
                            'id' => 'jumlah-potong',
                            'class' => 'form-control'
                        ]) ?>
                    </div>

                    <div class="mb-3">
                        <?= $form->field($model, 'sisa_kasbon')->textInput([
                            'maxlength' => true,
                            'readonly' => true,
                            'id' => 'sisa-kasbon',
                            'class' => 'form-control'
                        ]) ?>
                    </div>

                    <div class="mb-3">
                        <?= $form->field($model, 'status_potongan')->radioList(
                            [
                                0 => 'Belum Lunas',
                                1 => 'Lunas',
                            ],
                            [
                                'itemOptions' => ['class' => 'me-3'],
                                'id' => 'status-potongan'
                            ]
                        )->label('Status Potongan') ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 card-title">Detail Kasbon</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3 detail-item">
                        <div class="row">
                            <div class="col-6">
                                <p class="fw-bold">Jumlah Kasbon Awal</p>
                            </div>
                            <div class="col-6 text-end">
                                <p id="jumlah-kasbon-awal"><?= (int)$model->kasbon->jumlah_kasbon ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 detail-item">
                        <div class="row">
                            <div class="col-6">
                                <p class="fw-bold">Sisa Kasbon lama</p>
                            </div>
                            <div class="col-6 text-end">
                                <!-- UBAH INI: id="jumlah-kasbon" MENJADI id="sisa-kasbon-lama" -->
                                <p id="sisa-kasbon-lama"><?= (int)$model->sisa_kasbon ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 detail-item">
                        <div class="row">
                            <div class="col-6">
                                <p class="fw-bold">Jumlah Potongan</p>
                            </div>
                            <div class="col-6 text-end">
                                <p id="detail-jumlah-potong"><?= $model->jumlah_potong ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 detail-item">
                        <div class="row">
                            <div class="col-6">
                                <p class="fw-bold">Sisa Kasbon Baru</p>
                            </div>
                            <div class="col-6 text-end">
                                <p id="detail-sisa-kasbon-baru"><?= $model->sisa_kasbon ?></p>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 row">
        <div class="col-12">
            <div class="d-flex form-group">
                <button class="add-button" type="submit">
                    <i class="fas fa-save me-2"></i>Simpan
                </button>
                <button type="reset" class="reset-button ms-2">
                    <i class="fas fa-undo me-2"></i>Reset
                </button>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        // Format angka ke format Rupiah HANYA UNTUK DISPLAY
        function formatRupiah(angka) {
            if (!angka || angka === 0 || angka === '0') return 'Rp 0';

            // Convert to string and clean the input
            let cleanNumber = angka.toString();

            // Remove decimal part if exists (handle 20000000.00)
            if (cleanNumber.includes('.')) {
                cleanNumber = cleanNumber.split('.')[0];
            }

            // Remove any existing thousand separators
            cleanNumber = cleanNumber.replace(/\./g, '');

            // Convert to number
            let number = parseInt(cleanNumber);

            if (isNaN(number)) return 'Rp 0';

            // Format with thousand separators
            let formatted = number.toLocaleString('id-ID');

            return 'Rp ' + formatted;
        }

        // Parse format Rupiah ke angka
        function parseRupiah(rupiah) {
            if (!rupiah) return 0;

            let cleanNumber = rupiah.toString();

            // Remove decimal part if exists
            if (cleanNumber.includes('.')) {
                cleanNumber = cleanNumber.split('.')[0];
            }

            // Remove all non-digit characters
            cleanNumber = cleanNumber.replace(/\D/g, '');

            return parseInt(cleanNumber) || 0;
        }

        // Parse format Rupiah ke angka
        function parseRupiah(rupiah) {
            if (!rupiah) return 0;

            // Handle angka dengan koma (desimal)
            if (typeof rupiah === 'string' && rupiah.includes(',')) {
                // Ambil hanya bagian integer-nya (sebelum koma)
                rupiah = rupiah.split(',')[0];
            }

            // Hapus semua karakter non-digit
            return parseInt(rupiah.toString().replace(/[^\d]/g, '')) || 0;
        }
        // Fungsi untuk menghitung sisa kasbon BARU
        function hitungSisaKasbonBaru() {
            var sisaKasbonLama = parseRupiah($('#sisa-kasbon-lama').text());
            var jumlahPotong = $('#jumlah-potong').val() ? parseInt($('#jumlah-potong').val()) : 0;

            console.log('Sisa Lama:', sisaKasbonLama, 'Potongan:', jumlahPotong);

            var sisaKasbonBaru = sisaKasbonLama - jumlahPotong;

            // Pastikan sisa kasbon baru tidak negatif
            if (sisaKasbonBaru < 0) {
                sisaKasbonBaru = 0;
                jumlahPotong = sisaKasbonLama;
                $('#jumlah-potong').val(jumlahPotong);
            }

            // Update nilai sisa kasbon di form
            $('#sisa-kasbon').val(formatRupiah(sisaKasbonBaru));

            // Update nilai di detail
            $('#detail-jumlah-potong').text(formatRupiah(jumlahPotong));
            $('#detail-sisa-kasbon-baru').text(formatRupiah(sisaKasbonBaru));

            // Update status potongan otomatis
            if (sisaKasbonBaru <= 0) {
                $('#status-potongan input[value="1"]').prop('checked', true);
            } else {
                $('#status-potongan input[value="0"]').prop('checked', true);
            }
        }

        // Event ketika input jumlah potongan berubah - INPUT BIASA
        $('#jumlah-potong').on('input', function() {
            var input = $(this).val();

            // Hanya ambil angka saja, hapus semua non-digit
            var numericValue = input.replace(/[^\d]/g, '');

            // Set nilai bersih (tanpa Rp, tanpa format)
            $(this).val(numericValue);

            // Hitung sisa kasbon BARU
            hitungSisaKasbonBaru();
        });

        // Event ketika status potongan berubah
        $('#status-potongan input').on('change', function() {
            if ($(this).val() == '1') {
                // Jika status Lunas, set jumlah potongan sama dengan SISA KASBON LAMA
                var sisaKasbonLama = parseRupiah($('#sisa-kasbon-lama').text());
                $('#jumlah-potong').val(sisaKasbonLama);
                hitungSisaKasbonBaru();
            } else {
                // Jika status Belum Lunas, reset ke 0
                $('#jumlah-potong').val('0');
                hitungSisaKasbonBaru();
            }
        });

        // Format nilai awal saat halaman dimuat
        function formatNilaiAwal() {
            // Format jumlah kasbon awal (display saja)
            var jumlahKasbonAwal = parseRupiah($('#jumlah-kasbon-awal').text());
            $('#jumlah-kasbon-awal').text(formatRupiah(jumlahKasbonAwal));
            $('#total-kasbon-awal').text(formatRupiah(jumlahKasbonAwal));

            // Format sisa kasbon lama (display saja)
            var sisaKasbonLama = parseRupiah($('#sisa-kasbon-lama').text());
            $('#sisa-kasbon-lama').text(formatRupiah(sisaKasbonLama));

            // Format input jumlah potongan - HANYA ANGKA
            var jumlahPotongInput = $('#jumlah-potong').val();
            if (jumlahPotongInput && jumlahPotongInput.trim() !== '' && parseRupiah(jumlahPotongInput) > 0) {
                $('#jumlah-potong').val(parseRupiah(jumlahPotongInput));
            } else {
                $('#jumlah-potong').val('0');
            }

            // Format input sisa kasbon (display dengan Rupiah)
            $('#sisa-kasbon').val(formatRupiah(sisaKasbonLama));

            // Format detail (display dengan Rupiah)
            var currentPotong = parseRupiah($('#jumlah-potong').val());
            $('#detail-jumlah-potong').text(formatRupiah(currentPotong));
            $('#detail-sisa-kasbon-baru').text(formatRupiah(sisaKasbonLama - currentPotong));
        }

        // Jalankan format nilai awal
        formatNilaiAwal();

        // Tambahkan event untuk mencegah input karakter non-digit
        $('#jumlah-potong').on('keypress', function(e) {
            var char = String.fromCharCode(e.which);
            // Hanya izinkan digit
            if (!/[\d]/.test(char)) {
                e.preventDefault();
            }
        });

        // Saat form disubmit, pastikan nilai dikirim sebagai angka
        $('form').on('submit', function() {
            var numericValue = $('#jumlah-potong').val().replace(/[^\d]/g, '');
            $('#jumlah-potong').val(numericValue);
        });

        // Focus dan select semua text saat input diklik
        $('#jumlah-potong').on('click', function() {
            $(this).select();
        });
    });
</script>
<style>
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid rgba(0, 0, 0, 0.125);
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    }

    .detail-item {
        padding: 0.5rem 0;
    }

    .detail-item:not(:last-child) {
        border-bottom: 1px solid #e9ecef;
    }

    .btn {
        padding: 0.5rem 1.5rem;
    }

    .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
</style>