<?php

use yii\helpers\Url;
use yii\helpers\Html;

?>

<div class="modal fade" id="modalKasbon" tabindex="-1" aria-labelledby="modalKasbonLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class=" modal-header">
                <h5 class="modal-title" id="modalKasbonLabel">
                    <i class="fas fa-money-bill-wave me-2"></i>
                    Detail Kasbon Karyawan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Loading Indicator -->
                <div id="loadingKasbon" class="py-4 text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Memuat data kasbon...</p>
                </div>

                <!-- Error Message -->
                <div id="errorKasbon" class="alert alert-danger d-none">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <span id="errorMessage"></span>
                </div>

                <!-- Data Kasbon -->
                <div id="contentKasbon" class="d-none">
                    <!-- Info Karyawan -->
                    <div class="mb-4 row">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="py-2 card-body d-flex flex-column justify-content-center">
                                    <h6 class="mb-1 card-title">Informasi Karyawan</h6>
                                    <div class="d-flex justify-content-between">
                                        <small>Nama:</small>
                                        <small id="karyawanNamaKasbon">-</small>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <small>Bagian:</small>
                                        <small id="karyawanBagianKasbon">-</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Kasbon -->


                    <!-- Tabel Kasbon -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="15%">Tanggal Potong</th>
                                    <th width="15%" class="text-end">Jumlah Kasbon</th>
                                    <th width="15%" class="text-end">Angsuran</th>
                                    <th width="15%" class="text-end">Sisa Kasbon</th>
                                    <th width="20%">Status Potongan</th>
                                    <th width="15%">Tanggal Input</th>
                                </tr>
                            </thead>
                            <tbody id="tabelKasbonBody">
                                <!-- Data akan diisi via AJAX -->
                            </tbody>
                            <tfoot>
                                <tr class="table-secondary">
                                    <th colspan="2" class="text-end">Total Angsuran/Bulan:</th>
                                    <th colspan="5" id="totalKasbon" class="text-end">Rp 0</th>
                                </tr>
                            </tfoot>
                        </table>

                        <?php
                        $params = Yii::$app->request->get('TransaksiGaji', []);
                        $bulan = isset($params['bulan']) && $params['bulan'] != '' ? $params['bulan'] : date('m');
                        $tahun = isset($params['tahun']) && $params['tahun'] != '' ? $params['tahun'] : date('Y');
                        ?>

                        <?= Html::beginForm(['transaksi-gaji/pending-pembayaran'], 'post', ['id' => 'formPendingPembayaran']) ?>
                        <?= Html::hiddenInput('bulan', $bulan, ['id' => 'bulanInput']) ?>
                        <?= Html::hiddenInput('tahun', $tahun, ['id' => 'tahunInput']) ?>
                        <?= Html::hiddenInput('id_karyawan', '', ['id' => 'idKaryawanInput']) ?>
                        <?= Html::hiddenInput('id_kasbon', '', ['id' => 'idKasbonInput']) ?>

                        <button type="submit" class="mx-auto mb-2 btn btn-warning d-block">
                            Pending Pembayaran Bulan <?= $bulan ?> / <?= $tahun ?>
                        </button>
                        <?= Html::endForm() ?>

                        <?= Html::beginForm(['transaksi-gaji/batal-pending'], 'post', ['id' => 'formBatalPending']) ?>
                        <?= Html::hiddenInput('bulan', $bulan, ['id' => 'bulanBatalInput']) ?>
                        <?= Html::hiddenInput('tahun', $tahun, ['id' => 'tahunBatalInput']) ?>
                        <?= Html::hiddenInput('id_karyawan', '', ['id' => 'idKaryawanBatalInput']) ?>
                        <?= Html::hiddenInput('id_kasbon', '', ['id' => 'idKasbonBatalInput']) ?>

                        <button type="submit" class="mx-auto btn btn-danger d-block">
                            Batal Pending Bulan <?= $bulan ?> / <?= $tahun ?>
                        </button>
                        <?= Html::endForm() ?>


                    </div>

                    <!-- Empty State -->
                    <div id="emptyKasbon" class="py-5 text-center d-none">
                        <i class="mb-3 fas fa-inbox fa-3x text-muted"></i>
                        <h5 class="text-muted">Tidak ada data kasbon</h5>
                        <p class="text-muted">Belum ada kasbon untuk karyawan ini.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Tutup
                </button>
            </div>
        </div>
    </div>
</div>




<script>
    // Variabel untuk menyimpan data current
    let currentKaryawanId = null;
    let currentKaryawanNama = null;
    let currentKaryawanBagian = null;

    // Fungsi untuk load data kasbon
    function loadKasbonData(karyawanId, karyawanNama, karyawanBagian) {
        currentKaryawanId = karyawanId;
        currentKaryawanNama = karyawanNama;
        currentKaryawanBagian = karyawanBagian;

        // Show loading, hide content & error
        $('#loadingKasbon').removeClass('d-none');
        $('#contentKasbon').addClass('d-none');
        $('#errorKasbon').addClass('d-none');
        $('#emptyKasbon').addClass('d-none');

        // Set info karyawan
        $('#karyawanNamaKasbon').text(karyawanNama);
        $('#karyawanBagianKasbon').text(karyawanBagian);
        $('#idKaryawanInput').val(karyawanId);
        // AJAX request
        $.ajax({
            url: '<?= Url::to(['transaksi-gaji/get-kasbon-karyawan']) ?>',
            type: 'GET',
            data: {
                id_karyawan: karyawanId
            },
            success: function(response) {
                $('#loadingKasbon').addClass('d-none');

                if (response.success) {
                    displayKasbonData(response);
                    if (response.data && response.data.id_kasbon) {
                        $('#idKasbonInput').val(response.data.id_kasbon);
                        $('#idKasbonBatalInput').val(response.data.id_kasbon);
                    }
                    $('#idKaryawanInput').val(currentKaryawanId);
                    $('#idKaryawanBatalInput').val(currentKaryawanId);

                } else {
                    showError(response.error || 'Terjadi kesalahan saat memuat data');
                }
            },
            error: function(xhr, status, error) {
                $('#loadingKasbon').addClass('d-none');
                showError('Terjadi kesalahan: ' + error);
            }
        });
    }

    // Fungsi format Rupiah
    function formatRupiah(angka) {
        const number = parseFloat(angka) || 0;
        return 'Rp ' + number.toLocaleString('id-ID');
    }

    // Fungsi format tanggal
    function formatTanggal(tanggal) {
        if (!tanggal) return '-';
        const date = new Date(tanggal);
        return date.toLocaleDateString('id-ID');
    }

    // Fungsi format timestamp
    function formatTimestamp(timestamp) {
        if (!timestamp) return '-';
        const date = new Date(timestamp * 1000); // Convert Unix timestamp to milliseconds
        return date.toLocaleDateString('id-ID') + ' ' + date.toLocaleTimeString('id-ID');
    }

    // Fungsi untuk menampilkan status potongan
    function getStatusPotongan(status) {
        if (status == 1) {
            return '<span class="badge bg-success">lunas</span>';
        } else {
            return '<span class="badge bg-warning">Belum Lunas</span>';
        }
    }

    // Fungsi untuk menampilkan data kasbon
    function displayKasbonData(data) {

        const tbody = $('#tabelKasbonBody');
        tbody.empty();

        // Pastikan data ada
        if (!data.data) {
            $('#emptyKasbon').removeClass('d-none');
            $('#contentKasbon').addClass('d-none');
            $('#totalKasbon').text('Rp 0');
            return;
        }

        const kasbon = data?.data;
        const totalAngsuran = kasbon.angsuran || 0;


        // Tambahkan satu baris data
        const row = `
        <tr>
            <td>1</td>
            <td>${formatTanggal(kasbon.tanggal_potong)}</td>
            <td class="text-end fw-bold">${formatRupiah(kasbon.jumlah_kasbon)}</td>
            <td class="text-end text-success fw-bold">${formatRupiah(kasbon.angsuran)}</td>
            <td class="text-end text-danger fw-bold">${formatRupiah(kasbon.sisa_kasbon)}</td>
            <td>${getStatusPotongan(kasbon.status_potongan)}</td>
            <td>${formatTimestamp(kasbon.created_at)}</td>
        </tr>
    `;
        tbody.append(row);

        // Tampilkan total
        $('#totalKasbon').text(formatRupiah(totalAngsuran));

        // Tampilkan konten
        $('#contentKasbon').removeClass('d-none');
        $('#emptyKasbon').addClass('d-none');
    }


    // Fungsi untuk menampilkan error
    function showError(message) {
        $('#errorMessage').text(message);
        $('#errorKasbon').removeClass('d-none');
        $('#contentKasbon').addClass('d-none');
        $('#emptyKasbon').addClass('d-none');
    }

    // Event ketika modal ditutup
    $('#modalKasbon').on('hidden.bs.modal', function() {
        // Reset state
        currentKaryawanId = null;
        currentKaryawanNama = null;
        currentKaryawanBagian = null;

        // Reset tampilan
        $('#loadingKasbon').addClass('d-none');
        $('#contentKasbon').addClass('d-none');
        $('#errorKasbon').addClass('d-none');
        $('#emptyKasbon').addClass('d-none');
        $('#tabelKasbonBody').empty();
        $('#totalAngsuranBulan').text('Rp 0');
        $('#totalKasbon').text('Rp 0');
    });
</script>