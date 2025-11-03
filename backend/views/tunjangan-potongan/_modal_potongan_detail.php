<!-- MODAL POTONGAN DETAIL -->
<div class="modal fade" id="modalPotonganDetail" tabindex="-1" aria-labelledby="modalPotonganDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPotonganDetailLabel">Detail Potongan - <span id="modalPotonganKaryawanNama"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modalPotonganLoading" class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p>Memuat data potongan...</p>
                </div>
                <div id="modalPotonganContent" style="display: none;">
                    <!-- Content akan diisi via AJAX -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="5%">Aksi</th>
                                    <th width="35%">Nama Potongan</th>
                                    <th width="25%">Jumlah</th>
                                    <th width="15%">Satuan</th>
                                    <th width="20%">Status</th>
                                </tr>
                            </thead>
                            <tbody id="potonganTableBody">
                                <!-- Data akan diisi via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    <div id="potonganEmptyState" class="py-4 text-center" style="display: none;">
                        <i class="mb-3 fas fa-inbox fa-3x text-muted"></i>
                        <p class="text-muted">Tidak ada data potongan untuk karyawan ini.</p>
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
$('#modalPotonganDetail').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var karyawanId = button.data('karyawan-id');
    var karyawanNama = button.data('karyawan-nama');
    
    // Set modal title
    $('#modalPotonganKaryawanNama').text(karyawanNama);
    
    // Show loading, hide content
    $('#modalPotonganLoading').show();
    $('#modalPotonganContent').hide();
    
    // Load data via AJAX
    $.ajax({
        url: '/panel/potongan-detail/get-detail',
        type: 'GET',
        data: {
            id_karyawan: karyawanId
        },
        success: function(response) {
            // Hide loading
            $('#modalPotonganLoading').hide();
            
            // Check if response is successful and has data
            if (response.success && response.data && response.data.length > 0) {
                renderPotonganData(response.data);
                $('#modalPotonganContent').show();
            } else {
                showEmptyState();
                $('#modalPotonganContent').show();
            }
        },
        error: function() {
            $('#modalPotonganLoading').hide();
            $('#modalPotonganContent').html('<div class="alert alert-danger">Gagal memuat data potongan.</div>');
            $('#modalPotonganContent').show();
        }
    });
});


// Function to render potongan data
function renderPotonganData(data) {
    var tableBody = $('#potonganTableBody');
    var emptyState = $('#potonganEmptyState');
    
    // Clear existing data
    tableBody.empty();
    
    // Hide empty state, show table
    emptyState.hide();
    tableBody.closest('.table-responsive').show();
    
    // Populate table with data
    $.each(data, function(index, item) {
        var jumlahDisplay;

        // Jika satuan %, tampilkan jumlah asli → jumlah final
        if(item.satuan === '%') {
            jumlahDisplay = item.jumlah + ' → Rp ' + formatRupiah(item.jumlah_final);
        } else {
            jumlahDisplay = formatRupiah(item.jumlah); // Rp langsung
        }

        var row = '<tr>' +
            '<td>' + (index + 1) + '</td>' +
            '<td>' + 
                '<a href="/panel/potongan-detail/update?id_potongan_detail=' + item.id_potongan_detail + '&id_karyawan=' + item.id_karyawan + '" class="flex px-2 py-2 justify-content-center add-button">' +
                    '<i class="fas fa-edit"></i> ' +
                '</a>' +
            '</td>' +
            '<td>' + item.nama_potongan + '</td>' +
            '<td>' + jumlahDisplay + '</td>' +
            '<td>' + item.satuan + '</td>' +
            '<td>' + getStatusBadge(item.status) + '</td>' +
            '</tr>';
        tableBody.append(row);
    });
}




// Function to format number as Rupiah - tanpa nol di belakang koma
function formatRupiah(angka) {
    // Convert to number first
    var number = parseFloat(angka);
    
    // Format dengan Intl.NumberFormat
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 2
    }).format(number).replace(/,00$/, ''); // Hapus ,00 di akhir
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
    $('#potonganTableBody').empty();
    $('#potonganTableBody').closest('.table-responsive').hide();
    $('#potonganEmptyState').show();
}

// Reset modal ketika ditutup
$('#modalPotonganDetail').on('hidden.bs.modal', function () {
    $('#potonganTableBody').empty();
    $('#modalPotonganContent').hide();
});

JS;

$this->registerJs($js);
