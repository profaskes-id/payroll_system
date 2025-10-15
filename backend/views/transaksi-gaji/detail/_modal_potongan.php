<?php

use yii\helpers\Url;
?>

<div class="modal fade" id="modalPotongan" tabindex="-1" aria-labelledby="modalPotonganLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class=" modal-header">
                <h5 class="modal-title" id="modalPotonganLabel">
                    <i class="fas fa-money-bill-wave me-2"></i>
                    Detail Potongan Karyawan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Loading Indicator -->
                <div id="loadingPotongan" class="py-4 text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Memuat data potongan...</p>
                </div>

                <!-- Error Message -->
                <div id="errorPotongan" class="alert alert-danger d-none">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <span id="errorMessage"></span>
                </div>

                <!-- Data Potongan -->
                <div id="contentPotongan" class="d-none">
                    <!-- Info Karyawan -->
                    <div class="mb-4 row">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="py-2 card-body d-flex flex-column justify-content-center">
                                    <h6 class="mb-1 card-title">Informasi Karyawan</h6>
                                    <div class="d-flex justify-content-between">
                                        <small>Nama:</small>
                                        <small id="karyawanNamaPotongan">-</small>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <small>Bagian:</small>
                                        <small id="karyawanBagianPotongan">-</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Tabel Potongan -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="30%">Jenis Potongan</th>
                                    <th width="25%" class="text-end">Nominal</th>
                                    <th width="40%">Keterangan <sup>(% berdasarkan gaji)</sup></th>

                                </tr>
                            </thead>
                            <tbody id="tabelPotonganBody">
                                <!-- Data akan diisi via AJAX -->
                            </tbody>
                            <tfoot>
                                <tr class="table-secondary">
                                    <th colspan="2" class="text-end">Total Potongan:</th>

                                    <th colspan="2" id="totalPotongan" class="text-end">Rp 0</th>

                                </tr>
                        </table>
                    </div>

                    <!-- Empty State -->
                    <div id="emptyPotongan" class="py-5 text-center d-none">
                        <i class="mb-3 fas fa-inbox fa-3x text-muted"></i>
                        <h5 class="text-muted">Tidak ada data potongan</h5>
                        <p class="text-muted">Belum ada potongan untuk karyawan ini.</p>
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
    // Fungsi untuk load data potongan
    function loadPotonganData(karyawanId, karyawanNama, karyawanBagian) {
        currentKaryawanId = karyawanId;

        // Show loading, hide content & error
        $('#loadingPotongan').removeClass('d-none');
        $('#contentPotongan').addClass('d-none');
        $('#errorPotongan').addClass('d-none');
        $('#emptyPotongan').addClass('d-none');

        // Set info karyawan
        $('#karyawanNamaPotongan').text(karyawanNama);
        $('#karyawanBagianPotongan').text(karyawanBagian);

        // AJAX request
        $.ajax({
            url: '<?= Url::to(['transaksi-gaji/get-potongan-karyawan']) ?>',
            type: 'GET',
            data: {
                id_karyawan: karyawanId
            },
            success: function(response) {
                $('#loadingPotongan').addClass('d-none');

                if (response.success) {
                    displayPotonganData(response.data); // remove response.total
                } else {
                    showError(response.error || 'Terjadi kesalahan saat memuat data');
                }
            },

            error: function(xhr, status, error) {
                $('#loadingPotongan').addClass('d-none');
                showError('Terjadi kesalahan: ' + error);
            }
        });
    }

    // Fungsi format angka atau persen
    function formatPotonganValue(value) {
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

    // Fungsi format nilai potongan (bisa Rp atau %)
    function formatPotonganValue(value, satuan = 'Rp') {
        const number = parseFloat(value) || 0;
        if (satuan === '%') {
            return number + ' %';
        }
        return formatRupiah(number);
    }

    // Fungsi untuk menampilkan data potongan
    function displayPotonganData(data) {
        const tbody = $('#tabelPotonganBody');
        tbody.empty();

        let totalNominalFinal = 0;

        if (!data || data.length === 0) {
            $('#emptyPotongan').removeClass('d-none');
            $('#totalPotongan').text('Rp 0');
            return;
        }

        // Loop isi data tabel
        $.each(data, function(index, item) {
            const satuan = item.satuan || 'Rp';
            const jumlahDisplay = formatPotonganValue(item.jumlah, satuan);
            const nominalFinal = formatRupiah(item.nominal_final || 0);

            // Tambahkan ke total jika nominal_final valid
            totalNominalFinal += parseFloat(item.nominal_final || 0);

            const row = `
            <tr>
                <td>${index + 1}</td>
                <td>
                    <strong>${item.potongan || 'Potongan'}</strong>
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
        $('#totalPotongan').text(formatRupiah(totalNominalFinal));

        // Tampilkan konten
        $('#contentPotongan').removeClass('d-none');
        $('#emptyPotongan').addClass('d-none');
    }


    // Fungsi untuk menampilkan error
    function showError(message) {
        $('#errorMessage').text(message);
        $('#errorPotongan').removeClass('d-none');
        $('#contentPotongan').addClass('d-none');
        $('#emptyPotongan').addClass('d-none');
    }

    // Event ketika modal ditutup
    $('#modalPotongan').on('hidden.bs.modal', function() {
        // Reset state
        currentKaryawanId = null;
        currentKaryawanNama = null;
        currentKaryawanBagian = null;

        // Reset tampilan
        $('#loadingPotongan').addClass('d-none');
        $('#contentPotongan').addClass('d-none');
        $('#errorPotongan').addClass('d-none');
        $('#emptyPotongan').addClass('d-none');
        $('#tabelPotonganBody').empty();
    });
</script>