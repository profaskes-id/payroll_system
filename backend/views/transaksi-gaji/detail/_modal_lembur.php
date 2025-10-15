<?php

use yii\helpers\Url;
?>

<div class="modal fade" id="modalLembur" tabindex="-1" aria-labelledby="modalLemburLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLemburLabel">
                    <i class="fas fa-clock me-2"></i>
                    Detail Lembur Karyawan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Loading Indicator -->
                <div id="loadingLembur" class="py-4 text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Memuat data lembur...</p>
                </div>

                <!-- Error Message -->
                <div id="errorLembur" class="alert alert-danger d-none">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <span id="errorMessageLembur"></span>
                </div>

                <!-- Data Lembur -->
                <div id="contentLembur" class="d-none">
                    <!-- Info Karyawan -->
                    <div class="mb-4 row">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="py-2 card-body d-flex flex-column justify-content-center">
                                    <h6 class="mb-1 card-title">Informasi Karyawan</h6>
                                    <div class="d-flex justify-content-between">
                                        <small>Nama:</small>
                                        <small id="karyawanNamaLembur">-</small>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <small>Bagian:</small>
                                        <small id="karyawanBagianLembur">-</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Lembur -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="25%">Tanggal</th>
                                    <th width="20%">Waktu Lembur</th>
                                    <th width="15%" class="text-center">Durasi</th>
                                    <th width="15%" class="text-center">Hitungan Jam</th>
                                    <th width="20%" class="text-end">Pekerjaan</th>
                                </tr>
                            </thead>
                            <tbody id="tabelLemburBody">
                                <!-- Data akan diisi via AJAX -->
                            </tbody>
                            <tfoot>
                                <tr class="table-secondary fw-bold">
                                    <td colspan="3" class="text-end">Total:</td>
                                    <td id="totalDurasi" class="text-center">00:00:00</td>
                                    <td id="totalHitungan" class="text-center">0 jam</td>
                                    <td class="text-end">-</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Ringkasan Lembur -->
                    <div id="ringkasanLembur" class="mt-4 d-none">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card bg-light">
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="card-title">Ringkasan Lembur</h6>
                                        <div class="row">
                                            <div class="col-6">
                                                <p>Total Durasi:</p>
                                            </div>
                                            <div class="col-6 text-end">
                                                <p id="totalJamLembur" class="fw-bold">0 jam</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <p>Total Hitungan Jam:</p>
                                            </div>
                                            <div class="col-6 text-end">
                                                <p id="totalHitunganJam" class="fw-bold">0 jam</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <p>Tarif per Jam:</p>
                                            </div>
                                            <div class="col-6 text-end">
                                                <p id="tarifPerJam" class="fw-bold">Rp 0</p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-6">
                                                <p class="fw-bold">Subtotal Lembur:</p>
                                            </div>
                                            <div class="col-6 text-end">
                                                <p id="totalNominalLembur" class="fw-bold text-success">Rp 0</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Empty State -->
                    <div id="emptyLembur" class="py-5 text-center d-none">
                        <i class="mb-3 fas fa-inbox fa-3x text-muted"></i>
                        <h5 class="text-muted">Tidak ada data lembur</h5>
                        <p class="text-muted">Belum ada lembur untuk karyawan ini.</p>
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
    // Fungsi untuk load data lembur
    function loadLemburData(karyawanId, karyawanNama, karyawanBagian, bulan, tahun) {


        // Show loading, hide content & error
        $('#loadingLembur').removeClass('d-none');
        $('#contentLembur').addClass('d-none');
        $('#errorLembur').addClass('d-none');
        $('#emptyLembur').addClass('d-none');
        $('#ringkasanLembur').addClass('d-none');

        // Set info karyawan
        $('#karyawanNamaLembur').text(karyawanNama);
        $('#karyawanBagianLembur').text(karyawanBagian);

        // AJAX request
        $.ajax({
            url: '<?= Url::to(['transaksi-gaji/get-lembur-karyawan']) ?>',
            type: 'GET',
            data: {
                id_karyawan: karyawanId,
                bulan: bulan,
                tahun: tahun
            },
            success: function(response) {
                $('#loadingLembur').addClass('d-none');

                if (response.success) {
                    displayLemburData(response.data);
                } else {
                    showError(response.error || 'Terjadi kesalahan saat memuat data');
                }
            },
            error: function(xhr, status, error) {
                $('#loadingLembur').addClass('d-none');
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
            weekday: 'long',
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        };
        return date.toLocaleDateString('id-ID', options);
    }

    // Fungsi untuk format waktu (HH:MM:SS ke HH:MM)
    function formatWaktu(waktu) {
        if (!waktu) return '-';
        return waktu.substring(0, 5); // Ambil hanya HH:MM
    }

    // Fungsi untuk parse pekerjaan dari JSON string
    function parsePekerjaan(pekerjaanStr) {
        try {
            if (!pekerjaanStr) return ['Tidak ada keterangan'];

            // Jika sudah array, langsung return
            if (Array.isArray(pekerjaanStr)) {
                return pekerjaanStr;
            }

            // Jika string JSON, parse
            if (typeof pekerjaanStr === 'string' && pekerjaanStr.startsWith('[')) {
                return JSON.parse(pekerjaanStr);
            }

            // Jika string biasa, return sebagai array
            return [pekerjaanStr];
        } catch (e) {
            console.error('Error parsing pekerjaan:', e);
            return ['Tidak ada keterangan'];
        }
    }

    // Fungsi untuk mendapatkan status badge
    function getStatusLemburBadge(status) {
        if (status === 1) {
            return '<span class="badge bg-success">Disetujui</span>';
        } else if (status === 0) {
            return '<span class="badge bg-warning text-dark">Menunggu</span>';
        } else {
            return '<span class="badge bg-danger">Ditolak</span>';
        }
    }

    // Fungsi untuk menghitung total durasi
    function calculateTotalDurasi(data) {
        let totalSeconds = 0;

        $.each(data, function(index, item) {
            if (item.durasi) {
                const parts = item.durasi.split(':');
                if (parts.length === 3) {
                    const hours = parseInt(parts[0]) || 0;
                    const minutes = parseInt(parts[1]) || 0;
                    const seconds = parseInt(parts[2]) || 0;
                    totalSeconds += hours * 3600 + minutes * 60 + seconds;
                }
            }
        });

        const hours = Math.floor(totalSeconds / 3600);
        const minutes = Math.floor((totalSeconds % 3600) / 60);
        const seconds = totalSeconds % 60;

        return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }

    // Fungsi untuk menampilkan data lembur
    function displayLemburData(data) {
        const tbody = $('#tabelLemburBody');
        tbody.empty();


        // Periksa struktur data yang diterima
        console.log('Data lembur diterima:', data);

        // Ambil data jam lembur dan gaji per jam
        const jamLembur = data.jam_lembur || [];
        const gajiPerJam = parseFloat(data.gaji_perjam) || 0;

        if (!jamLembur || jamLembur.length === 0) {
            $('#emptyLembur').removeClass('d-none');
            $('#totalDurasi').text('00:00:00');
            $('#totalHitungan').text('0 jam');
            $('#totalJamLembur').text('0 jam');
            $('#totalHitunganJam').text('0 jam');
            $('#totalNominalLembur').text('Rp 0');
            $('#tarifPerJam').text(formatRupiah(0));
            $('#ringkasanLembur').addClass('d-none');
            return;
        }

        let totalHitunganJam = 0;
        let totalDurasi = calculateTotalDurasi(jamLembur);

        // Loop isi data tabel
        $.each(jamLembur, function(index, item) {
            const pekerjaanList = parsePekerjaan(item.pekerjaan);
            const hitunganJam = parseFloat(item.hitungan_jam) || 0;
            totalHitunganJam += hitunganJam;

            const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>
                        <div class="fw-semibold">${formatTanggal(item.tanggal)}</div>
                        <div class="mt-1">
                            ${getStatusLemburBadge(item.status)}
                            ${item.disetujui_pada ? `<br><small class="text-muted">Disetujui: ${formatTanggal(item.disetujui_pada)}</small>` : ''}
                        </div>
                    </td>
                    <td>
                        <small>${formatWaktu(item.jam_mulai)} - ${formatWaktu(item.jam_selesai)}</small>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-info text-dark">${item.durasi || '00:00:00'}</span>
                    </td>
                    <td class="text-center fw-bold text-warning">
                        ${hitunganJam} jam
                    </td>
                    <td class="text-end">
                        <div class="text-start">
                            ${pekerjaanList.map(pekerjaan => `<div class="small">• ${pekerjaan}</div>`).join('')}
                            ${item.catatan_admin ? `<div class="mt-1"><small class="text-muted"><strong>Catatan:</strong> ${item.catatan_admin}</small></div>` : ''}
                        </div>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });

        // Tampilkan total di tabel
        $('#totalDurasi').text(totalDurasi);
        $('#totalHitungan').text(totalHitunganJam.toFixed(1) + ' jam');

        // Tampilkan ringkasan lembur
        $('#totalJamLembur').text(totalDurasi);
        $('#totalHitunganJam').text(totalHitunganJam.toFixed(1) + ' jam');
        $('#tarifPerJam').text(formatRupiah(gajiPerJam));

        // Hitung total nominal
        const totalNominal = totalHitunganJam * gajiPerJam;
        $('#totalNominalLembur').text(formatRupiah(totalNominal));

        // Tampilkan ringkasan
        $('#ringkasanLembur').removeClass('d-none');




        // Tampilkan konten
        $('#contentLembur').removeClass('d-none');
        $('#emptyLembur').addClass('d-none');
    }

    // Fungsi untuk menampilkan error
    function showError(message) {
        $('#errorMessageLembur').text(message);
        $('#errorLembur').removeClass('d-none');
        $('#contentLembur').addClass('d-none');
        $('#emptyLembur').addClass('d-none');
        $('#ringkasanLembur').addClass('d-none');
    }

    // Event ketika modal ditutup
    $('#modalLembur').on('hidden.bs.modal', function() {
        // Reset tampilan
        $('#loadingLembur').addClass('d-none');
        $('#contentLembur').addClass('d-none');
        $('#errorLembur').addClass('d-none');
        $('#emptyLembur').addClass('d-none');
        $('#ringkasanLembur').addClass('d-none');
        $('#tabelLemburBody').empty();
        $('#statusInfoLembur').empty();

        // Reset info
        $('#karyawanNamaLembur').text('-');
        $('#karyawanBagianLembur').text('-');
        $('#totalDurasi').text('00:00:00');
        $('#totalHitungan').text('0 jam');
        $('#totalJamLembur').text('0 jam');
        $('#totalHitunganJam').text('0 jam');
        $('#tarifPerJam').text('Rp 0');
        $('#totalNominalLembur').text('Rp 0');
    });
</script>