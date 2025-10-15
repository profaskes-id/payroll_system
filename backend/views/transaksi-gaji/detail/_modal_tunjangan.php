<?php

use yii\helpers\Url;
?>

<div class="modal fade" id="modalTunjangan" tabindex="-1" aria-labelledby="modalTunjanganLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class=" modal-header">
                <h5 class="modal-title" id="modalTunjanganLabel">
                    <i class="fas fa-money-bill-wave me-2"></i>
                    Detail Tunjangan Karyawan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Loading Indicator -->
                <div id="loadingTunjangan" class="py-4 text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Memuat data tunjangan...</p>
                </div>

                <!-- Error Message -->
                <div id="errorTunjangan" class="alert alert-danger d-none">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <span id="errorMessage"></span>
                </div>

                <!-- Data Tunjangan -->
                <div id="contentTunjangan" class="d-none">
                    <!-- Info Karyawan -->
                    <div class="mb-4 row">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="py-2 card-body d-flex flex-column justify-content-center">
                                    <h6 class="mb-1 card-title">Informasi Karyawan</h6>
                                    <div class="d-flex justify-content-between">
                                        <small>Nama:</small>
                                        <small id="karyawanNamaTunjangan">-</small>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <small>Bagian:</small>
                                        <small id="karyawanBagianTunjangan">-</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Tabel Tunjangan -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="30%">Jenis Tunjangan</th>
                                    <th width="25%" class="text-end">Nominal</th>
                                    <th width="40%">Keterangan <sup>(% berdasarkan gaji)</sup></th>
                                </tr>
                            </thead>
                            <tbody id="tabelTunjanganBody">
                                <!-- Data akan diisi via AJAX -->
                            </tbody>
                            <tfoot>
                                <tr class="table-secondary">
                                    <th colspan="2" class="text-end">Total Tunjangan:</th>

                                    <th colspan="2" id="totalTunjangan" class="text-end">Rp 0</th>

                                </tr>
                        </table>
                    </div>

                    <!-- Empty State -->
                    <div id="emptyTunjangan" class="py-5 text-center d-none">
                        <i class="mb-3 fas fa-inbox fa-3x text-muted"></i>
                        <h5 class="text-muted">Tidak ada data tunjangan</h5>
                        <p class="text-muted">Belum ada tunjangan untuk karyawan ini.</p>
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

    // Fungsi untuk load data tunjangan
    function loadTunjanganData(karyawanId, karyawanNama, karyawanBagian) {
        currentKaryawanId = karyawanId;

        // Show loading, hide content & error
        $('#loadingTunjangan').removeClass('d-none');
        $('#contentTunjangan').addClass('d-none');
        $('#errorTunjangan').addClass('d-none');
        $('#emptyTunjangan').addClass('d-none');

        // Set info karyawan
        $('#karyawanNamaTunjangan').text(karyawanNama);
        $('#karyawanBagianTunjangan').text(karyawanBagian);

        // AJAX request
        $.ajax({
            url: '<?= Url::to(['transaksi-gaji/get-tunjangan-karyawan']) ?>',
            type: 'GET',
            data: {
                id_karyawan: karyawanId
            },
            success: function(response) {
                $('#loadingTunjangan').addClass('d-none');

                if (response.success) {
                    displayTunjanganData(response.data); // remove response.total
                } else {
                    showError(response.error || 'Terjadi kesalahan saat memuat data');
                }
            },

            error: function(xhr, status, error) {
                $('#loadingTunjangan').addClass('d-none');
                showError('Terjadi kesalahan: ' + error);
            }
        });
    }

    // Fungsi format angka atau persen
    function formatTunjanganValue(value) {
        const str = (value || '').toString().trim();

        // Jika string mengandung %, langsung kembalikan tanpa format
        if (str.includes('%')) {
            return str;
        }

        // Kalau kosong atau bukan angka, kembalikan 'Rp 0'
        const number = parseFloat(str);
        if (isNaN(number)) {
            return 'Rp 0';
        }

        return 'Rp ' + number.toLocaleString('id-ID');
    }


    // Fungsi format Rupiah
    // Fungsi format angka sebagai Rupiah
    function formatRupiah(angka) {
        const number = parseFloat(angka) || 0;
        return 'Rp ' + number.toLocaleString('id-ID');
    }

    // Fungsi format nilai tunjangan (bisa Rp atau %)
    function formatTunjanganValue(value, satuan = 'Rp') {
        const number = parseFloat(value) || 0;
        if (satuan === '%') {
            return number + ' %';
        }
        return formatRupiah(number);
    }

    // Fungsi untuk menampilkan data tunjangan
    function displayTunjanganData(data) {
        const tbody = $('#tabelTunjanganBody');
        tbody.empty();

        let totalNominalFinal = 0;

        if (!data || data.length === 0) {
            $('#emptyTunjangan').removeClass('d-none');
            $('#totalTunjangan').text('Rp 0');
            return;
        }

        // Loop isi data tabel
        $.each(data, function(index, item) {
            const satuan = item.satuan || 'Rp';
            const jumlahDisplay = formatTunjanganValue(item.jumlah, satuan);
            const nominalFinal = formatRupiah(item.nominal_final || 0);

            // Tambahkan ke total jika nominal_final valid
            totalNominalFinal += parseFloat(item.nominal_final || 0);

            const row = `
            <tr>
                <td>${index + 1}</td>
                <td>
                    <strong>${item.nama_tunjangan || 'Tunjangan'}</strong>
                    ${satuan ? `<br><small class="text-muted">Satuan: ${satuan}</small>` : ''}
                </td>
                <td class="text-end fw-bold text-success">${nominalFinal}</td>
                <td>
                    <div class="small">
                        <div><strong>Jumlah:</strong> ${jumlahDisplay}</div>
                        ${item.status ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Tidak Aktif</span>'}
                    </div>
                </td>
            </tr>
        `;
            tbody.append(row);
        });

        // Tampilkan total
        $('#totalTunjangan').text(formatRupiah(totalNominalFinal));

        // Tampilkan konten
        $('#contentTunjangan').removeClass('d-none');
        $('#emptyTunjangan').addClass('d-none');
    }


    // Fungsi untuk menampilkan error
    function showError(message) {
        $('#errorMessage').text(message);
        $('#errorTunjangan').removeClass('d-none');
        $('#contentTunjangan').addClass('d-none');
        $('#emptyTunjangan').addClass('d-none');
    }

    // Event ketika modal ditutup
    $('#modalTunjangan').on('hidden.bs.modal', function() {
        // Reset state
        currentKaryawanId = null;
        currentKaryawanNama = null;
        currentKaryawanBagian = null;

        // Reset tampilan
        $('#loadingTunjangan').addClass('d-none');
        $('#contentTunjangan').addClass('d-none');
        $('#errorTunjangan').addClass('d-none');
        $('#emptyTunjangan').addClass('d-none');
        $('#tabelTunjanganBody').empty();
    });
</script>