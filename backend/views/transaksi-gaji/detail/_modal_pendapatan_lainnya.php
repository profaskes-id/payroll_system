<?php

use yii\helpers\Url; ?>

<div class="modal fade" id="modalPendapatanlainnya" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="text-white modal-header bg-success">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle me-2"></i>
                    Detail Pendapatan Lainnya
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <!-- Loading -->
                <div id="loadingPendapatanLainnya" class="py-4 text-center">
                    <div class="spinner-border text-success"></div>
                    <p class="mt-2 text-muted">Memuat data pendapatan...</p>
                </div>

                <!-- Error -->
                <div id="errorPendapatan" class="alert alert-danger d-none">
                    <span id="errorPendapatanMessage"></span>
                </div>

                <!-- Content -->
                <div id="contentPendapatan" class="d-none">
                    <div class="mb-3 d-flex justify-content-end">
                        <a href="#"
                            id="btnAddPendapatan"
                            class="btn btn-sm btn-success">
                            <i class="fas fa-plus me-1"></i> Tambah Pendapatan Lainnya
                        </a>
                    </div>


                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-primary">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="5%" class="text-center">
                                        <img src="<?= Yii::getAlias('@root') ?>/images/icons/grid.svg" alt="grid">
                                    </th>

                                    <th width="30%">Keterangan</th>
                                    <th width="30%" class="text-end">Nominal</th>
                                </tr>
                            </thead>

                            <tbody id="tabelPendapatanBody"></tbody>
                            <tfoot>
                                <tr class="table-secondary">
                                    <th colspan="2" class="text-end">Total:</th>
                                    <th colspan="3" id="totalPendapatan" class="text-end">Rp 0</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div id="emptyPendapatan" class="py-5 text-center d-none">
                        <i class="mb-3 fas fa-inbox fa-3x text-muted"></i>
                        <p class="text-muted">Tidak ada pendapatan lainnya</p>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>

        </div>
    </div>
</div>

<script>
    // Simpan context aktif (dipakai tombol ADD)
    let currentIdKaryawan = null;
    let currentBulan = null;
    let currentTahun = null;

    function loadPendapatanLainnyaData(pendapatan, idKaryawan, bulan, tahun) {

        // simpan context
        currentIdKaryawan = idKaryawan;
        currentBulan = bulan;
        currentTahun = tahun;

        // SET URL tombol ADD (INI KUNCINYA)
        const addUrl =
            `/panel/pendapatan-potongan-lainnya/create` +
            `?id_karyawan=${idKaryawan}` +
            `&bulan=${bulan}` +
            `&tahun=${tahun}` +
            `&pendapatan=1`;

        $('#btnAddPendapatan').attr('href', addUrl);

        $('#loadingPendapatanLainnya').removeClass('d-none');
        $('#contentPendapatan').addClass('d-none');
        $('#errorPendapatan').addClass('d-none');
        $('#emptyPendapatan').addClass('d-none');
        $('#tabelPendapatanBody').empty();

        $.ajax({
            url: '<?= Url::to(['transaksi-gaji/get-pendapatan-potongan-lainnya']) ?>',
            type: 'GET',
            dataType: 'json',
            data: {
                id_karyawan: idKaryawan,
                bulan: bulan,
                tahun: tahun,
                is_pendapatan: 1,
                is_potongan: 0
            },
            success: function(res) {
                $('#loadingPendapatanLainnya').addClass('d-none');

                if (!res.success || !res.data || res.data.length === 0) {
                    $('#emptyPendapatan').removeClass('d-none');
                    $('#totalPendapatan').text('Rp 0');
                    $('#contentPendapatan').removeClass('d-none'); // tetap tampil
                    return;
                }

                let total = 0;

                res.data.forEach((item, i) => {
                    const jumlah = parseFloat(item.jumlah) || 0;
                    total += jumlah;

                    $('#tabelPendapatanBody').append(`
                    <tr>
                        <td>${i + 1}</td>
                        <td class="text-center">
                            <button
                                class="btn btn-sm btn-danger btn-delete-pendapatan"
                                data-id="${item.id_ppl}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                        <td>${item.keterangan ?? '-'}</td>
                        <td class="text-end fw-semibold">
                            Rp ${jumlah.toLocaleString('id-ID')}
                        </td>
                    </tr>
                `);
                });

                $('#totalPendapatan').text('Rp ' + total.toLocaleString('id-ID'));
                $('#contentPendapatan').removeClass('d-none');
            },
            error: function() {
                $('#loadingPendapatanLainnya').addClass('d-none');
                $('#errorPendapatan').removeClass('d-none');
                $('#errorPendapatanMessage').text('Gagal memuat data');
            }
        });
    }




    $(document).on('click', '.btn-delete-pendapatan', function() {
        const idPpl = $(this).data('id');

        if (!confirm('Yakin ingin menghapus pendapatan ini?')) {
            return;
        }

        $.ajax({
            url: `/panel/pendapatan-potongan-lainnya/delete?id_ppl=${idPpl}`,
            type: 'POST',
            dataType: 'json', // penting
            success: function(res) {
                if (res.success) {
                    alert(res.message);
                    window.location.href = '/panel/transaksi-gaji/index';
                } else {
                    alert('Gagal menghapus data');
                }
            },
            error: function() {
                alert('Gagal menghapus data');
            }
        });

    });
</script>