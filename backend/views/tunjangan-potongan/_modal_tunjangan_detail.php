<!-- MODAL TUNJANGAN DETAIL -->
<div class="modal fade" id="modalTunjanganDetail" tabindex="-1" aria-labelledby="modalTunjanganDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTunjanganDetailLabel">Detail Tunjangan - <span id="modalTunjanganKaryawanNama"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modalTunjanganLoading" class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p>Memuat data tunjangan...</p>
                </div>
                <div id="modalTunjanganContent" style="display: none;">
                    <!-- Content akan diisi via AJAX -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="5%">Aksi</th>
                                    <th width="35%">Nama Tunjangan</th>
                                    <th width="25%">Jumlah</th>
                                    <th width="15%">Satuan</th>
                                    <th width="20%">Status</th>
                                </tr>
                            </thead>
                            <tbody id="tunjanganTableBody">
                                <!-- Data akan diisi via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    <div id="tunjanganEmptyState" class="py-4 text-center" style="display: none;">
                        <i class="mb-3 fas fa-inbox fa-3x text-muted"></i>
                        <p class="text-muted">Tidak ada data tunjangan untuk karyawan ini.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<?php
$js = <<<JS
// Handle modal show event untuk TUNJANGAN
$('#modalTunjanganDetail').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var karyawanId = button.data('karyawan-id');
    var karyawanNama = button.data('karyawan-nama');
    
    // Set modal title
    $('#modalTunjanganKaryawanNama').text(karyawanNama);
    
    // Show loading, hide content
    $('#modalTunjanganLoading').show();
    $('#modalTunjanganContent').hide();
    
    // Load data via AJAX
    $.ajax({
        url: '/panel/tunjangan-detail/get-detail',
        type: 'GET',
        data: {
            id_karyawan: karyawanId
        },
        success: function(response) {
            // Hide loading
            $('#modalTunjanganLoading').hide();
            
            // Check if response is successful and has data
            if (response.success && response.data && response.data.length > 0) {
                renderTunjanganData(response.data);
                $('#modalTunjanganContent').show();
            } else {
                showEmptyState();
                $('#modalTunjanganContent').show();
            }
        },
        error: function() {
            $('#modalTunjanganLoading').hide();
            $('#modalTunjanganContent').html('<div class="alert alert-danger">Gagal memuat data tunjangan.</div>');
            $('#modalTunjanganContent').show();
        }
    });
});

// Function to render tunjangan data
function renderTunjanganData(data) {
    var tableBody = $('#tunjanganTableBody');
    var emptyState = $('#tunjanganEmptyState');
    
    // Clear existing data
    tableBody.empty();
    
    // Hide empty state, show table
    emptyState.hide();
    tableBody.closest('.table-responsive').show();
    
    // Populate table with data
$.each(data, function(index, item) {
    console.info(item)
    var row = '<tr>' +
        '<td>' + (index + 1) + '</td>' +
        '<td>' + 
            '<a href="/panel/tunjangan-detail/update?id_tunjangan_detail=' + item.id_tunjangan_detail + '&id_karyawan=' + item.id_karyawan + '" class="flex px-2 py-2 justify-content-center add-button">' +
                '<i class="fas fa-edit"></i> ' +
            '</a>' +
        '</td>' +
        '<td>' + item.nama_tunjangan + '</td>' +
        '<td>' + formatJumlah(item.jumlah, item.satuan) + '</td>' +
        '<td>' + item.satuan + '</td>' +
        '<td>' + getStatusBadge(item.status) + '</td>' +
        '</tr>';
    tableBody.append(row);
});
}

// Function to format jumlah based on satuan
function formatJumlah(jumlah, satuan) {
    if (satuan === '%') {
        return jumlah + '%';
    } else if (satuan === 'Rp') {
        return 'Rp ' + formatRupiah(jumlah);
    } else {
        return jumlah;
    }
}

// Function to format number as Rupiah
function formatRupiah(angka) {
    var number_string = angka.toString().replace(/[^,\d]/g, ''),
        split = number_string.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        var separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return rupiah;
}

// Function to get status badge
function getStatusBadge(status) {
    if (status == 1) {
        return '<span class="badge bg-success">Aktif</span>';
    } else {
        return '<span class="badge bg-danger">Non-Aktif</span>';
    }
}

// Function to show empty state
function showEmptyState() {
    $('#tunjanganTableBody').empty();
    $('#tunjanganTableBody').closest('.table-responsive').hide();
    $('#tunjanganEmptyState').show();
}

// Reset modal ketika ditutup
$('#modalTunjanganDetail').on('hidden.bs.modal', function () {
    $('#tunjanganTableBody').empty();
    $('#modalTunjanganContent').hide();
});

JS;

$this->registerJs($js);
