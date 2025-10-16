<?php

use yii\helpers\Url;
?>
<div class="modal fade" id="modalPotonganTerlambat" tabindex="-1" aria-labelledby="modalPotonganTerlambatLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPotonganTerlambatLabel">
                    <i class="fas fa-money-bill-wave me-2"></i>
                    Detail Potongan Terlambat Karyawan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Loading Indicator -->
                <div id="loadingPotonganTerlambat" class="py-4 text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Memuat data potongan terlambat...</p>
                </div>

                <!-- Error Message -->
                <div id="errorPotonganTerlambat" class="alert alert-danger d-none">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <span id="errorMessage"></span>
                </div>

                <!-- Data Potongan Terlambat -->
                <div id="contentPotonganTerlambat" class="d-none">
                    <!-- Info Karyawan -->
                    <div class="mb-4 row">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="py-2 card-body d-flex flex-column justify-content-center">
                                    <h6 class="mb-1 card-title">Informasi Karyawan</h6>
                                    <div class="d-flex justify-content-between">
                                        <small>Nama:</small>
                                        <small id="karyawanNamaTerlambat">-</small>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <small>Bagian:</small>
                                        <small id="karyawanBagianTerlambat">-</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Tabel Detail Keterlambatan -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="25%">Tanggal</th>
                                    <th width="20%" class="text-center">Lama Terlambat</th>
                                    <th width="25%" class="text-center">Potongan per Menit <sup>(berdasarkan gaji)</sup></th>
                                    <th width="25%" class="text-end">Subtotal Potongan</th>
                                </tr>
                            </thead>
                            <tbody id="tabelPotonganTerlambatBody">
                                <!-- Data akan diisi via AJAX -->
                            </tbody>
                            <tfoot>
                                <tr class="table-secondary fw-bold">
                                    <td colspan="4" class="text-end">Total Potongan Terlambat:</td>
                                    <td id="totalPotonganTerlambat" class="text-end">Rp 0</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Empty State -->
                    <div id="emptyPotonganTerlambat" class="py-5 text-center d-none">
                        <i class="mb-3 fas fa-inbox fa-3x text-muted"></i>
                        <h5 class="text-muted">Tidak ada data keterlambatan</h5>
                        <p class="text-muted">Tidak ada catatan keterlambatan untuk karyawan ini.</p>
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
    // Fungsi untuk load data potongan terlambat
    function loadPotonganTerlambatData(karyawanId, karyawanNama, karyawanBagian) {
        // Show loading, hide content & error
        $('#loadingPotonganTerlambat').removeClass('d-none');
        $('#contentPotonganTerlambat').addClass('d-none');
        $('#errorPotonganTerlambat').addClass('d-none');
        $('#emptyPotonganTerlambat').addClass('d-none');

        // Set info karyawan
        $('#karyawanNamaTerlambat').text(karyawanNama);
        $('#karyawanBagianTerlambat').text(karyawanBagian);

        // AJAX request
        $.ajax({
            url: '<?= Url::to(['transaksi-gaji/get-potongan-terlambat-karyawan']) ?>',
            type: 'GET',
            data: {
                id_karyawan: karyawanId
            },
            success: function(response) {
                $('#loadingPotonganTerlambat').addClass('d-none');

                // Perbaikan: Langsung proses response tanpa cek response.success
                // karena format response langsung berisi data, bukan {success: true, data: ...}
                displayPotonganTerlambatData(response);
            },
            error: function(xhr, status, error) {
                $('#loadingPotonganTerlambat').addClass('d-none');
                showError('Terjadi kesalahan: ' + error);
                console.error('AJAX Error:', error);
                console.error('Status:', status);
                console.error('Response:', xhr.responseText);
            }
        });
    }

    // Fungsi untuk mengkonversi waktu HH:MM:SS ke menit
    function timeToMinutes(timeString) {
        if (!timeString) return 0;

        const parts = timeString.split(':');
        if (parts.length !== 3) return 0;

        const hours = parseInt(parts[0]) || 0;
        const minutes = parseInt(parts[1]) || 0;
        const seconds = parseInt(parts[2]) || 0;

        return hours * 60 + minutes + Math.round(seconds / 60);
    }

    // Fungsi format Rupiah
    function formatRupiah(angka) {
        const number = parseFloat(angka) || 0;
        return 'Rp ' + number.toLocaleString('id-ID');
    }

    // Fungsi untuk menampilkan data potongan terlambat
    function displayPotonganTerlambatData(data) {
        const tbody = $('#tabelPotonganTerlambatBody');
        tbody.empty();

        console.log('Data received:', data); // Debug log

        // Periksa apakah data ada dan memiliki struktur yang benar
        if (!data || typeof data !== 'object') {
            showError('Data yang diterima tidak valid');
            return;
        }

        // Tampilkan info potongan
        $('#potonganPerMenit').text(formatRupiah(data.potonganPerMenit || 0));
        $('#totalLamaTerlambat').text(data.lama_terlambat || '0 menit');
        $('#totalPotonganDisplay').text(formatRupiah(data.potonganSemuaTerlambat || 0));

        if (!data.filteredTerlambat || data.filteredTerlambat.length === 0) {
            $('#emptyPotonganTerlambat').removeClass('d-none');
            $('#totalPotonganTerlambat').text(formatRupiah(0));
            return;
        }

        let totalPotongan = 0;

        // Loop isi data tabel
        $.each(data.filteredTerlambat, function(index, item) {
            const lamaMenit = timeToMinutes(item.terlambat);
            const subtotal = lamaMenit * (data.potonganPerMenit || 0);
            totalPotongan += subtotal;

            // Format tanggal
            const tanggal = new Date(item.tanggal).toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            const row = `
            <tr>
                <td>${index + 1}</td>
                <td>
                    <strong>${tanggal}</strong>
                    <br><small class="text-muted">${item.tanggal}</small>
                </td>
                <td class="text-center">
                    <span class="badge bg-warning text-dark fs-6">${item.terlambat}</span>
                    <br><small class="text-muted">${lamaMenit} menit</small>
                </td>
                <td class="text-center">${formatRupiah(data.potonganPerMenit)}</td>
                <td class="text-end fw-bold text-danger">${formatRupiah(subtotal)}</td>
            </tr>
        `;
            tbody.append(row);
        });

        // Tampilkan total - gunakan total dari response atau hitungan manual
        const finalTotal = data.potonganSemuaTerlambat || totalPotongan;
        $('#totalPotonganTerlambat').text(formatRupiah(finalTotal));

        // Tampilkan konten
        $('#contentPotonganTerlambat').removeClass('d-none');
        $('#emptyPotonganTerlambat').addClass('d-none');
    }

    // Fungsi untuk menampilkan error
    function showError(message) {
        $('#errorMessage').text(message);
        $('#errorPotonganTerlambat').removeClass('d-none');
        $('#contentPotonganTerlambat').addClass('d-none');
        $('#emptyPotonganTerlambat').addClass('d-none');
    }

    // Event ketika modal ditutup
    $('#modalPotonganTerlambat').on('hidden.bs.modal', function() {
        // Reset tampilan
        $('#loadingPotonganTerlambat').addClass('d-none');
        $('#contentPotonganTerlambat').addClass('d-none');
        $('#errorPotonganTerlambat').addClass('d-none');
        $('#emptyPotonganTerlambat').addClass('d-none');
        $('#tabelPotonganTerlambatBody').empty();

        // Reset info
        $('#karyawanNamaTerlambat').text('-');
        $('#karyawanBagianTerlambat').text('-');
        $('#potonganPerMenit').text('Rp 0');
        $('#totalLamaTerlambat').text('0 menit');
        $('#totalPotonganDisplay').text('Rp 0');
    });
</script>