<?php

use yii\helpers\Url;
?>

<div class="modal fade" id="modalPotonganAbsensi" tabindex="-1" aria-labelledby="modalPotonganAbsensiLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class=" modal-header">
                <h5 class="modal-title" id="modalPotonganAbsensiLabel">
                    <i class="fas fa-money-bill-wave me-2"></i>
                    Detail Potongan Absensi Karyawan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Loading Indicator -->
                <div id="loadingPotonganAbsensi" class="py-4 text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Memuat data potongan absensi...</p>
                </div>

                <!-- Error Message -->
                <div id="errorPotonganAbsensi" class="alert alert-danger d-none">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <span id="errorMessage"></span>
                </div>

                <!-- Data PotonganAbsensi -->
                <div id="contentPotonganAbsensi" class="d-none">
                    <!-- Info Karyawan -->
                    <div class="mb-4 row">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="py-2 card-body d-flex flex-column justify-content-center">
                                    <h6 class="mb-1 card-title">Informasi Karyawan</h6>
                                    <div class="d-flex justify-content-between">
                                        <small>Nama:</small>
                                        <small id="karyawanNamaAbsensi">-</small>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <small>Bagian:</small>
                                        <small id="karyawanBagianAbsensi">-</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ringkasan Potongan -->
                    <div class="mb-4 row">
                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title text-primary">
                                        <i class="fas fa-calendar-times me-2"></i>Potongan Alfa
                                    </h6>
                                    <div class="d-flex justify-content-between">
                                        <small>Jumlah Alfa:</small>
                                        <small id="jumlahAlfa">0 hari</small>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <small>Gaji Per Hari:</small>
                                        <small id="gajiPerHari">Rp 0</small>
                                    </div>
                                    <div class="d-flex justify-content-between fw-bold">
                                        <small>Total Potongan:</small>
                                        <small id="totalPotonganAlfa">Rp 0</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-warning">
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title text-warning">
                                        <i class="fas fa-home me-2"></i>Potongan WFH
                                    </h6>
                                    <div class="d-flex justify-content-between">
                                        <small>Jumlah WFH:</small>
                                        <small id="jumlahWFH">0 hari</small>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <small>Potongan per WFH:</small>
                                        <small id="potonganPerWFH">50%</small>
                                    </div>
                                    <div class="d-flex justify-content-between fw-bold">
                                        <small>Total Potongan:</small>
                                        <small id="totalPotonganWFH">Rp 0</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Detail Potongan -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="25%">Jenis Potongan</th>
                                    <th width="15%" class="text-center">Jumlah</th>
                                    <th width="20%" class="text-center">Nominal per Hari</th>
                                    <th width="20%" class="text-end">Total Potongan</th>
                                    <th width="15%" class="text-center">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody id="tabelPotonganAbsensiBody">
                                <!-- Data akan diisi via AJAX -->
                            </tbody>
                            <tfoot>
                                <tr class="table-secondary fw-bold">
                                    <td colspan="4" class="text-end">Total Seluruh Potongan:</td>
                                    <td id="totalSemuaPotongan" class="text-end">Rp 0</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Empty State -->
                    <div id="emptyPotonganAbsensi" class="py-5 text-center d-none">
                        <i class="mb-3 fas fa-inbox fa-3x text-muted"></i>
                        <h5 class="text-muted">Tidak ada data potongan absensi</h5>
                        <p class="text-muted">Belum ada potongan absensi untuk karyawan ini.</p>
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
    // Fungsi untuk load data potongan absensi
    function loadPotonganAbsensiData(karyawanId, karyawanNama, karyawanBagian, total_alfa, gaji_perhari, jumlah_wfh) {

        $('#loadingPotonganAbsensi').removeClass('d-none');
        $('#contentPotonganAbsensi').addClass('d-none');
        $('#errorPotonganAbsensi').addClass('d-none');
        $('#emptyPotonganAbsensi').addClass('d-none');

        // Set info karyawan
        $('#karyawanNamaAbsensi').text(karyawanNama);
        $('#karyawanBagianAbsensi').text(karyawanBagian);

        // AJAX request
        $.ajax({
            url: '<?= Url::to(['transaksi-gaji/get-potongan-absensi-karyawan']) ?>',
            type: 'GET',
            data: {
                id_karyawan: karyawanId,
                total_alfa_range: total_alfa,
                gaji_perhari: gaji_perhari,
                jumlah_wfh: jumlah_wfh
            },
            success: function(response) {
                $('#loadingPotonganAbsensi').addClass('d-none');

                if (response.success) {
                    displayPotonganAbsensiData(response);
                } else {
                    showError(response.error || 'Terjadi kesalahan saat memuat data');
                }
            },
            error: function(xhr, status, error) {
                $('#loadingPotonganAbsensi').addClass('d-none');
                showError('Terjadi kesalahan: ' + error);
            }
        });
    }

    // Fungsi format Rupiah
    function formatRupiah(angka) {
        const number = parseFloat(angka) || 0;
        return 'Rp ' + number.toLocaleString('id-ID');
    }

    // Fungsi untuk menampilkan data potongan absensi
    function displayPotonganAbsensiData(data) {
        const tbody = $('#tabelPotonganAbsensiBody');
        tbody.empty();

        if (!data) {
            $('#emptyPotonganAbsensi').removeClass('d-none');
            return;
        }

        // Hitung potongan berdasarkan data
        const gajiPerHari = parseInt(data.gaji_perhari) || 0;
        const jumlahAlfa = data.total_alfa_range || 0;
        const potonganWFHPersen = parseInt(data.potonganwfhsehari) || 0;
        const jumlahWFH = data.jumlah_wfh || 0;

        // Hitung nominal potongan

        const potonganAlfaPerHari = gajiPerHari;

        const totalPotonganAlfa = data.total_potongan_absensi;

        const potonganWFHPerHari = (data?.potonganwfhsehari / 100) * gajiPerHari;

        const totalPotonganWFH = jumlahWFH * potonganWFHPerHari;

        const totalSemuaPotongan = totalPotonganAlfa + totalPotonganWFH;

        // Update ringkasan
        $('#jumlahAlfa').text(jumlahAlfa + ' hari');
        $('#gajiPerHari').text(formatRupiah(gajiPerHari));
        $('#totalPotonganAlfa').text(formatRupiah(totalPotonganAlfa));

        $('#jumlahWFH').text(jumlahWFH + ' hari');
        $('#potonganPerWFH').text(potonganWFHPersen + '%');
        $('#totalPotonganWFH').text(formatRupiah(totalPotonganWFH));

        // Isi tabel
        let rowNumber = 1;

        // Baris untuk potongan alfa
        if (jumlahAlfa > 0) {
            const rowAlfa = `
                <tr>
                    <td>${rowNumber++}</td>
                    <td>
                        <strong>Potongan Alfa</strong>
                        <br><small class="text-muted">Tidak hadir kerja</small>
                    </td>
                    <td class="text-center">${jumlahAlfa} hari</td>
                    <td class="text-center">${formatRupiah(potonganAlfaPerHari)}</td>
                    <td class="text-end fw-bold text-danger">${formatRupiah(totalPotonganAlfa)}</td>
                    <td class="text-center">
                        <span class="badge bg-danger">Alfa</span>
                    </td>
                </tr>
            `;
            tbody.append(rowAlfa);
        }

        // Baris untuk potongan WFH
        if (jumlahWFH > 0) {
            const rowWFH = `
                <tr>
                    <td>${rowNumber++}</td>
                    <td>
                        <strong>Potongan WFH</strong>
                        <br><small class="text-muted">Work From Home</small>
                    </td>
                    <td class="text-center">${jumlahWFH} hari</td>
                    <td class="text-center">${formatRupiah(potonganWFHPerHari)}<br><small>(${potonganWFHPersen}% dari gaji)</small></td>
                    <td class="text-end fw-bold text-warning">${formatRupiah(totalPotonganWFH)}</td>
                    <td class="text-center">
                        <span class="badge bg-warning">WFH</span>
                    </td>
                </tr>
            `;
            tbody.append(rowWFH);
        }

        // Tampilkan total
        $('#totalSemuaPotongan').text(formatRupiah(totalSemuaPotongan));

        // Tampilkan konten atau empty state
        if (jumlahAlfa > 0 || jumlahWFH > 0) {
            $('#contentPotonganAbsensi').removeClass('d-none');
            $('#emptyPotonganAbsensi').addClass('d-none');
        } else {
            $('#emptyPotonganAbsensi').removeClass('d-none');
            $('#contentPotonganAbsensi').addClass('d-none');
        }
    }

    // Fungsi untuk menampilkan error
    function showError(message) {
        $('#errorMessage').text(message);
        $('#errorPotonganAbsensi').removeClass('d-none');
        $('#contentPotonganAbsensi').addClass('d-none');
        $('#emptyPotonganAbsensi').addClass('d-none');
    }

    // Event ketika modal ditutup
    $('#modalPotonganAbsensi').on('hidden.bs.modal', function() {
        // Reset tampilan
        $('#loadingPotonganAbsensi').addClass('d-none');
        $('#contentPotonganAbsensi').addClass('d-none');
        $('#errorPotonganAbsensi').addClass('d-none');
        $('#emptyPotonganAbsensi').addClass('d-none');
        $('#tabelPotonganAbsensiBody').empty();

        // Reset ringkasan
        $('#jumlahAlfa').text('0 hari');
        $('#gajiPerHari').text('Rp 0');
        $('#totalPotonganAlfa').text('Rp 0');
        $('#jumlahWFH').text('0 hari');
        $('#potonganPerWFH').text('50%');
        $('#totalPotonganWFH').text('Rp 0');
        $('#totalSemuaPotongan').text('Rp 0');
    });
</script>