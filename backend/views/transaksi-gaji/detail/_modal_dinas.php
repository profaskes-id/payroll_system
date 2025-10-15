<?php

use yii\helpers\Url;
?>

<div class="modal fade" id="modalDinas" tabindex="-1" aria-labelledby="modalDinasLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDinasLabel">
                    <i class="fas fa-business-time me-2"></i>
                    Detail Dinas Luar Karyawan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Loading Indicator -->
                <div id="loadingDinas" class="py-4 text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Memuat data dinas...</p>
                </div>

                <!-- Error Message -->
                <div id="errorDinas" class="alert alert-danger d-none">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <span id="errorMessageDinas"></span>
                </div>

                <!-- Data Dinas -->
                <div id="contentDinas" class="d-none">
                    <!-- Info Karyawan -->
                    <div class="mb-4 row">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="py-2 card-body d-flex flex-column justify-content-center">
                                    <h6 class="mb-1 card-title">Informasi Karyawan</h6>
                                    <div class="d-flex justify-content-between">
                                        <small>Nama:</small>
                                        <small id="karyawanNamaDinas">-</small>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <small>Bagian:</small>
                                        <small id="karyawanBagianDinas">-</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Dinas -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="45%">Keterangan Perjalanan</th>
                                    <th width="20%">Tanggal</th>
                                    <th width="15%" class="text-end">Estimasi Biaya</th>
                                    <th width="15%" class="text-end">Biaya Disetujui</th>
                                </tr>
                            </thead>
                            <tbody id="tabelDinasBody">
                                <!-- Data akan diisi via AJAX -->
                            </tbody>
                            <tfoot>
                                <tr class="table-secondary fw-bold">
                                    <td colspan="3" class="text-end">Total Biaya Dinas:</td>
                                    <td id="totalEstimasi" class="text-end">Rp 0</td>
                                    <td id="totalDinas" class="text-end">Rp 0</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Status Info -->
                    <div id="statusInfo" class="mt-3">
                        <!-- Info status akan diisi via JavaScript -->
                    </div>

                    <!-- Empty State -->
                    <div id="emptyDinas" class="py-5 text-center d-none">
                        <i class="mb-3 fas fa-inbox fa-3x text-muted"></i>
                        <h5 class="text-muted">Tidak ada data dinas</h5>
                        <p class="text-muted">Belum ada dinas luar untuk karyawan ini.</p>
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
    // Fungsi untuk load data dinas
    function loadDinasData(karyawanId, karyawanNama, karyawanBagian, bulan, tahun) {

        console.info(
            karyawanId,
            karyawanNama,
            karyawanBagian,
            bulan,
            tahun
        )
        // Show loading, hide content & error
        $('#loadingDinas').removeClass('d-none');
        $('#contentDinas').addClass('d-none');
        $('#errorDinas').addClass('d-none');
        $('#emptyDinas').addClass('d-none');

        // Set info karyawan
        $('#karyawanNamaDinas').text(karyawanNama);
        $('#karyawanBagianDinas').text(karyawanBagian);

        // AJAX request
        $.ajax({
            url: '<?= Url::to(['transaksi-gaji/get-dinas-karyawan']) ?>',
            type: 'GET',
            data: {
                id_karyawan: karyawanId,
                bulan: bulan,
                tahun: tahun
            },
            success: function(response) {
                $('#loadingDinas').addClass('d-none');

                if (response.success) {
                    displayDinasData(response.data);
                } else {
                    showError(response.error || 'Terjadi kesalahan saat memuat data');
                }
            },
            error: function(xhr, status, error) {
                $('#loadingDinas').addClass('d-none');
                showError('Terjadi kesalahan: ' + error);
            }
        });
    }

    // Fungsi format Rupiah
    function formatRupiah(angka) {
        const number = parseFloat(angka) || 0;
        return 'Rp ' + number.toLocaleString('id-ID');
    }

    // Fungsi format tanggal Indonesia
    function formatTanggal(tanggal) {
        if (!tanggal) return '-';

        const date = new Date(tanggal);
        const options = {
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        };
        return date.toLocaleDateString('id-ID', options);
    }

    // Fungsi untuk mendapatkan status badge
    function getStatusBadge(status, statusDibayar) {
        if (status === 1 && statusDibayar === 1) {
            return '<span class="badge bg-success">Lunas</span>';
        } else if (status === 1 && statusDibayar === 0) {
            return '<span class="badge bg-warning text-dark">Belum Dibayar</span>';
        } else if (status === 0) {
            return '<span class="badge bg-secondary">Menunggu</span>';
        } else {
            return '<span class="badge bg-danger">Ditolak</span>';
        }
    }

    // Fungsi untuk menampilkan data dinas
    function displayDinasData(data) {
        const tbody = $('#tabelDinasBody');
        tbody.empty();
        $('#statusInfo').empty();

        if (!data || data.length === 0) {
            $('#emptyDinas').removeClass('d-none');
            $('#totalEstimasi').text('Rp 0');
            $('#totalDinas').text('Rp 0');
            return;
        }

        let totalEstimasi = 0;
        let totalDisetujui = 0;
        let adaYangBelumDibayar = false;

        // Loop isi data tabel
        $.each(data, function(index, item) {
            const estimasiBiaya = parseFloat(item.estimasi_biaya) || 0;
            const biayaDisetujui = parseFloat(item.biaya_yang_disetujui) || 0;

            totalEstimasi += estimasiBiaya;
            totalDisetujui += biayaDisetujui;

            // Cek apakah ada yang belum dibayar
            if (item.status === 1 && item.status_dibayar === 0) {
                adaYangBelumDibayar = true;
            }

            // Format tanggal
            const tanggalMulai = formatTanggal(item.tanggal_mulai);
            const tanggalSelesai = formatTanggal(item.tanggal_selesai);
            const tanggalDisplay = tanggalMulai === tanggalSelesai ?
                tanggalMulai :
                `${tanggalMulai} - ${tanggalSelesai}`;

            const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>
                        <div class="fw-semibold">${item.keterangan_perjalanan || 'Tidak ada keterangan'}</div>
                        <div class="mt-1">
                            ${getStatusBadge(item.status, item.status_dibayar)}
                            ${item.catatan_admin ? `<br><small class="text-muted"><strong>Catatan:</strong> ${item.catatan_admin}</small>` : ''}
                        </div>
                    </td>
                    <td>
                        <small>${tanggalDisplay}</small>
                        ${item.disetujui_pada ? `<br><small class="text-muted">Disetujui: ${formatTanggal(item.disetujui_pada)}</small>` : ''}
                    </td>
                    <td class="text-end">${formatRupiah(estimasiBiaya)}</td>
                    <td class="text-end fw-bold text-success">${formatRupiah(biayaDisetujui)}</td>
                </tr>
            `;
            tbody.append(row);
        });

        // Tampilkan total
        $('#totalEstimasi').text(formatRupiah(totalEstimasi));
        $('#totalDinas').text(formatRupiah(totalDisetujui));

        // Tampilkan info status
        if (adaYangBelumDibayar) {
            $('#statusInfo').html(`
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Terdapat dinas luar yang belum dibayar. Biaya tersebut akan termasuk dalam perhitungan gaji.
                </div>
            `);
        }

        // Tampilkan konten
        $('#contentDinas').removeClass('d-none');
        $('#emptyDinas').addClass('d-none');
    }

    // Fungsi untuk menampilkan error
    function showError(message) {
        $('#errorMessageDinas').text(message);
        $('#errorDinas').removeClass('d-none');
        $('#contentDinas').addClass('d-none');
        $('#emptyDinas').addClass('d-none');
    }

    // Event ketika modal ditutup
    $('#modalDinas').on('hidden.bs.modal', function() {
        // Reset tampilan
        $('#loadingDinas').addClass('d-none');
        $('#contentDinas').addClass('d-none');
        $('#errorDinas').addClass('d-none');
        $('#emptyDinas').addClass('d-none');
        $('#tabelDinasBody').empty();
        $('#statusInfo').empty();

        // Reset info
        $('#karyawanNama').text('-');
        $('#karyawanBagian').text('-');
        $('#totalEstimasi').text('Rp 0');
        $('#totalDinas').text('Rp 0');
    });
</script>