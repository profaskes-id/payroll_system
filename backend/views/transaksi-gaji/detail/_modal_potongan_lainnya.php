<?php

use yii\helpers\Url;
?>

<div class="modal fade" id="modalPotonganlainnya" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="text-white modal-header bg-danger">
                <h5 class="modal-title">
                    <i class="fas fa-minus-circle me-2"></i>
                    Detail Potongan Lainnya
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <!-- Loading -->
                <div id="loadingPotonganLainnya" class="py-4 text-center">
                    <div class="spinner-border text-danger"></div>
                    <p class="mt-2 text-muted">Memuat data potongan...</p>
                </div>

                <!-- Error -->
                <div id="errorPotongan" class="alert alert-danger d-none">
                    <span id="errorPotonganMessage"></span>
                </div>

                <!-- Content -->
                <div id="contentPotonganLainya">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-danger">
                                <tr>
                                    <th width="5%">#</th>

                                    <th width="30%">Keterangan</th>
                                    <th width="30%" class="text-end">Nominal</th>
                                </tr>
                            </thead>

                            <tbody id="tabelPotonganLainnyaBody"></tbody>
                            <tfoot>
                                <tr class="table-secondary">
                                    <th colspan="2" class="text-end">Total:</th>
                                    <th colspan="3" id="Lainnya" class="text-end">Rp 0</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div id="emptyPotonganLainnya" class="py-5 text-center d-none">
                        <i class="mb-3 fas fa-inbox fa-3x text-muted"></i>
                        <p class="text-muted">Tidak ada potongan lainnya</p>
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
    let currentIdKaryawanPotongan = null;
    let currentBulanPotongan = null;
    let currentTahunPotongan = null;

    function loadPotonganLainnyaData(potongan, idKaryawan, bulan, tahun) {

        currentIdKaryawanPotongan = idKaryawan;
        currentBulanPotongan = bulan;
        currentTahunPotongan = tahun;

        // set URL tombol Add
        const addUrl = `/panel/pendapatan-potongan-lainnya/create?id_karyawan=${idKaryawan}&bulan=${bulan}&tahun=${tahun}&potongan=1`;
        $('#btnAddPotongan').attr('href', addUrl);

        $('#loadingPotonganLainnya').removeClass('d-none');
        $('#contentPotonganLainya').addClass('d-none');
        $('#errorPotongan').addClass('d-none');
        $('#emptyPotonganLainnya').addClass('d-none');


        $.ajax({
            url: '<?= Url::to(['transaksi-gaji/get-pendapatan-potongan-lainnya']) ?>',
            type: 'GET',
            dataType: 'json',
            data: {
                id_karyawan: idKaryawan,
                bulan: bulan,
                tahun: tahun,
                is_pendapatan: 0,
                is_potongan: 1
            },
            success: function(res) {

                // spinner harus selalu hilang dulu
                $('#loadingPotonganLainnya').hide();

                // content selalu tampil
                $('#contentPotonganLainya').removeClass('d-none');

                if (!res.success || !res.data || res.data.length === 0) {
                    // tampil pesan kosong
                    $('#emptyPotonganLainnya').removeClass('d-none');
                    $('#Lainnya').text('Rp 0');
                    // kosongkan tabel
                    $('#tabelPotonganLainnyaBody').empty();
                    return;
                }

                let total = 0;

                // Kosongkan tabel terlebih dahulu
                $('#tabelPotonganLainnyaBody').empty();

                res.data.forEach((item, i) => {

                    const jumlah = parseFloat(item.jumlah) || 0;
                    total += jumlah;

                    $('#tabelPotonganLainnyaBody').append(`
        <tr>
            <td>${i + 1}</td>

            <td>${item.keterangan ?? '-'}</td>
            <td class="text-end fw-semibold">Rp ${jumlah.toLocaleString('id-ID')}</td>

        </tr>
        `);
                });

                $('#Lainnya').text('Rp ' + total.toLocaleString('id-ID'));
                $('#emptyPotonganLainnya').addClass('d-none');
            },
            error: function() {
                $('#loadingPotonganLainnya').addClass('d-none');
                $('#errorPotongan').removeClass('d-none');
                $('#errorPotonganMessage').text('Gagal memuat data');
            }
        });

    }
</script>