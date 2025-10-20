    <?php

    use backend\models\helpers\KaryawanHelper;
    use backend\models\helpers\PeriodeGajiHelper;
    use backend\models\Karyawan;
    use backend\models\PeriodeGaji;
    use backend\models\Tanggal;
    use backend\models\TransaksiGaji;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\ActionColumn;
    use yii\grid\GridView;
    use yii\widgets\ActiveForm;

    $karyawan = new KaryawanHelper();


    /** @var yii\web\View $this */
    /** @var backend\models\TransaksiGajiSearch $searchModel */
    /** @var yii\data\ActiveDataProvider $dataProvider */

    $this->title = Yii::t('app', 'Transaksi Gaji');
    $this->params['breadcrumbs'][] = $this->title;

    // Register CSS dan JS
    $this->registerCss(
        <<<CSS
        /* Sticky header dan kolom */
        .table thead th {
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #f8f9fa;
        }
        
        .table td.sticky-col,
        .table th.sticky-col {
            position: sticky;
            left: 0;
            z-index: 9;
            background-color: white;
        }
        
        .table thead th.sticky-col {
            z-index: 11;
            background-color: #f8f9fa;
        }
        
        /* Action buttons styling */
        .action-buttons {
            display: flex;
            gap: 4px;
            justify-content: center;
        }
        
        .karyawan-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .position-badge {
            min-width: 140px;
            /* border: 1px solid ; */
        }
    CSS
    );

    $this->registerJs(
        <<<JS
        // Fungsi untuk konfirmasi aksi
        function confirmAction(message, url) {
            if (confirm(message)) {
                window.location.href = url;
            }
        }
        
        // Event handlers untuk tombol aksi
        document.addEventListener('DOMContentLoaded', function() {
            // Tombol Lock/Process
            document.querySelectorAll('.btn-lock-action').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = this.getAttribute('data-url');
                    const isProcessed = this.getAttribute('data-processed') === 'true';
                    
                    if (isProcessed) {
                        confirmAction(
                            'Apakah Anda yakin ingin melihat detail transaksi gaji ini?',
                            url
                        );
                    } else {
                        confirmAction(
                            'Apakah Anda yakin ingin memproses gaji untuk karyawan ini?',
                            url
                        );
                    }
                });
            });
            
            // Tombol Regenerate
            document.querySelectorAll('.btn-regenerate').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = this.getAttribute('data-url');
                    confirmAction(
                        'Apakah Anda yakin ingin meregenerasi transaksi gaji ini? Tindakan ini akan menghapus data yang sudah ada dan membuat yang baru.',
                        url
                    );
                });
            });
        });
    JS
    );
    ?>

    <?php
    $this->registerCss(
        <<<CSS
    .action-buttons {
        display: flex;
        gap: 4px;
        justify-content: center;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    /* Hover effects */
    .btn-detail:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
        transform: translateY(-1px);
    }

    .btn-regenerate:hover {
        background-color: #ffca2c;
        border-color: #ffc720;
        transform: translateY(-1px);
    }

    .btn-process:hover {
        background-color: #198754;
        border-color: #157347;
        transform: translateY(-1px);
    }

    /* Modal styling */
    .modal-content {
        border-radius: 10px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        border-radius: 10px 10px 0 0;
    }

    .modal-title {
        font-weight: 600;
        color: #495057;
    }
    CSS
    );
    ?>

    <!-- jQuery (required for some Yii widgets) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap 5 (Bundle = termasuk Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <?php
    // Include semua modal
    echo $this->render('detail/_modal_potongan');
    echo $this->render('detail/_modal_tunjangan');
    echo $this->render('detail/_modal_potongan_absensi');
    echo $this->render('detail/_modal_potongan_terlambat');
    echo $this->render('detail/_modal_dinas');
    echo $this->render('detail/_modal_lembur');
    ?>


    <!-- <div class="col-12 col-md-9">
        <button style="width: 100%;" class="add-button" type="submit" data-toggle="collapse" data-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample">
            <i class="fas fa-search"></i>
            <span>
                Search
            </span>
        </button>
    </div> -->
    <!-- <div style="margin-top: 10px;"> -->
    <!-- <div class="collapse width" id="collapseWidthExample"> -->
    <!-- <div style="width: 100%;"> -->
    <?php // $this->render('_search', ['model' => $model]) 
    ?>
    <!-- </div> -->
    <!-- </div> -->
    <!-- </div> -->
    <div style="width: 100%;">
        <?= $this->render('_search', ['model' => $model]) ?>
    </div>
    <div class="transaksi-gaji-index">
        <div class="card">
            <div class="bg-white card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 card-title">
                        <i class="fas fa-money-bill-wave me-2"></i>
                        <?= Html::encode($this->title) ?>
                    </h5>
                    <div class="flex-wrap btn-group">
                        <?= Html::beginForm(['transaksi-gaji/generate-gaji'], 'post', ['id' => 'generate-gaji-form']); ?>
                        <?= Html::hiddenInput('karyawanID', $karyawanID) ?>
                        <?= Html::submitButton('Generate Gaji', [
                            'class' => 'add-button',
                            'id' => 'generate-gaji-button'
                        ]) ?>
                        <?= Html::endForm(); ?>


                        <a href="/panel/transaksi-gaji/report" class="mx-2 reset-button bg-warning">
                            <i class="fas fa-print me-1"></i> Cetak Transaksi
                        </a>
                    </div>
                </div>
            </div>

            <div class="p-0 card-body">
                <div class="table-responsive" style="max-height: 70vh;">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'tableOptions' => ['class' => 'table table-bordered table-hover mb-0'],
                        'options' => ['class' => 'table-container'],
                        'columns' => [
                            [
                                'headerOptions' => [
                                    'class' => 'text-center align-middle',
                                    'style' => 'width: 40px;'
                                ],
                                'contentOptions' => ['class' => 'text-center align-middle'],
                                'class' => 'yii\grid\SerialColumn'
                            ],

                            [
                                'attribute' => 'id_transaksi_gaji',
                                'header' => '<i class="fas fa-cogs"></i>',
                                'headerOptions' => [
                                    'class' => 'text-center align-middle',
                                    'style' => 'width: 100px;'
                                ],
                                'contentOptions' => ['class' => 'text-center align-middle'],
                                'format' => 'raw',
                                'value' => function ($model) {

                                    $actions = '';

                                    if (isset($model['id_transaksi_gaji']) && $model['id_transaksi_gaji'] != null) {
                                        $actions = '
                <div class="action-buttons">
                    <!-- Tombol Detail -->
                    <button type="button" 
                            class="btn btn-danger btn-sm btn-detail"
                            data-bs-toggle="modal" 
                            data-bs-target="#confirmModal"
                            data-action="detail"
                            data-url="' . Url::to(['deleterow', 'id_karyawan' => $model['id_karyawan'], 'bulan' => $model['bulan'], 'tahun' => $model['tahun']]) . '"
                            data-karyawan="' . Html::encode($model['nama'] ?? 'Karyawan') . '"
                            title="Lihat Detail">
                        <i class="fas fa-trash"></i>
                    </button>
                    
                    <!-- Tombol Regenerate -->
                    <button type="button" 
                            class="btn btn-warning btn-sm btn-regenerate"
                            data-bs-toggle="modal" 
                            data-bs-target="#confirmModal"
                            data-action="regenerate"
                            data-url="' . Url::to(['generate-gaji-one', 'id_karyawan' => $model['id_karyawan']]) . '"
                            data-karyawan="' . Html::encode($model['nama'] ?? 'Karyawan') . '"
                            title="Regenerate">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            ';
                                    } else {
                                        $actions = '
                <div class="action-buttons">
                    <button type="button" 
                            class="btn btn-success btn-sm btn-process"
                            data-bs-toggle="modal" 
                            data-bs-target="#confirmModal"
                            data-action="process"
                            data-url="' . Url::to(['generate-gaji-one', 'id_karyawan' => $model['id_karyawan']]) . '"
                            data-karyawan="' . Html::encode($model['nama'] ?? 'Karyawan') . '"
                            title="Proses Gaji">
                        <i class="fas fa-lock"></i>
                    </button>
                </div>
            ';
                                    }

                                    return $actions;
                                }
                            ],

                            // Kolom untuk Nama Karyawan
                            [
                                'attribute' => 'nama',
                                'label' => "Nama Karyawan",
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'sticky-col align-middle'],
                                'contentOptions' => ['class' => 'sticky-col align-middle'],
                                'value' => function ($model) {
                                    $nama = $model['nama'] ?? "-";
                                    return '<div class="fw-semibold">' . Html::encode($nama) . '</div>';
                                }
                            ],

                            // Kolom untuk Bagian & Jabatan
                            [
                                'attribute' => 'nama_bagian',
                                'label' => "Bagian & Jabatan",
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'align-middle', 'style' => 'width: 150px;'],
                                'contentOptions' => function ($model) {
                                    $color = $model['color'] ?? '#e9ecef';
                                    return [
                                        'class' => 'align-middle',
                                        'style' => "background-color: {$color};"
                                    ];
                                },
                                'value' => function ($model) {
                                    $bagian = $model['nama_bagian'] ?? '-';
                                    $jabatan = $model['jabatan'] ?? '-';

                                    return '
            <div>
                <div class="fw-bold text-dark small">' . Html::encode($bagian) . '</div>
                <hr class="mx-0 my-1" />
                <div class=" small">' . Html::encode($jabatan) . '</div>
            </div>
        ';
                                }
                            ],

                            [
                                'attribute' => 'nominal_gaji',
                                'label' => "Gaji Pokok",
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'text-end align-middle'],
                                'contentOptions' => ['class' => 'text-end align-middle'],
                                'value' => function ($model) {
                                    $gajiPokok = $model['nominal_gaji'] ?? 0;
                                    return '<span class="fw-semibold">Rp ' . number_format($gajiPokok, 0, ',', '.') . '</span>';
                                }
                            ],

                            [
                                'attribute' => 'tunjangan_karyawan',
                                'label' => "Tunjangan",
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'text-end align-middle'],
                                'contentOptions' => ['class' => 'text-end align-middle'],
                                'value' => function ($model) {
                                    $tunjangan = $model['tunjangan_karyawan'] ?? 0;
                                    $karyawanNama = $model['nama'] ?? 'Karyawan';
                                    $karyawanBagian = $model['nama_bagian'] ?? '-';

                                    return '
            <button type="button" 
                    class="p-0 btn btn-link text-success text-decoration-none btn-tunjangan-modal"
                    data-bs-toggle="modal" 
                    data-bs-target="#modalTunjangan"
                    onclick="loadTunjanganData(' . $model['id_karyawan'] . ', \'' . addslashes($karyawanNama) . '\', \'' . addslashes($karyawanBagian) . '\')"
                    title="Lihat Detail Tunjangan">
                <span class="fw-semibold">Rp ' . number_format($tunjangan, 0, ',', '.') . '</span>
            </button>
        ';
                                }
                            ],
                            [
                                'attribute' => 'potongan_karyawan',
                                'label' => "Potongan",
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'text-end align-middle'],
                                'contentOptions' => ['class' => 'text-end align-middle'],
                                'value' => function ($model) {
                                    $potongan = $model['potongan_karyawan'] ?? 0;
                                    $karyawanNama = $model['nama'] ?? 'Karyawan';
                                    $karyawanBagian = $model['nama_bagian'] ?? '-';

                                    return '
            <button type="button" 
                    class="p-0 btn btn-link text-danger text-decoration-none btn-potongan-modal"
                    data-bs-toggle="modal" 
                    data-bs-target="#modalPotongan"
                    onclick="loadPotonganData(' . $model['id_karyawan'] . ', \'' . addslashes($karyawanNama) . '\', \'' . addslashes($karyawanBagian) . '\')"
                    title="Lihat Detail Potongan">
                <span class="fw-semibold">Rp ' . number_format($potongan, 0, ',', '.') . '</span>
            </button>
        ';
                                }
                            ],


                            [
                                'attribute' => 'total_absensi',
                                'label' => "Total Hadir",
                                'headerOptions' => ['class' => 'text-center align-middle'],
                                'contentOptions' => ['class' => 'text-center align-middle'],
                                'value' => function ($model) {
                                    return $model['total_absensi'] ?? 0;
                                }
                            ],

                            [
                                'attribute' => 'total_alfa_range',
                                'label' => "Total Tidak Hadir",
                                'headerOptions' => ['class' => 'text-center align-middle'],
                                'contentOptions' => ['class' => 'text-center align-middle'],
                                'value' => function ($model) {
                                    return $model['total_alfa_range'] ?? 0;
                                }
                            ],

                            [
                                'attribute' => 'potongan_absensi',
                                'label' => "Potongan Absensi",
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'text-end align-middle'],
                                'contentOptions' => ['class' => 'text-end align-middle'],
                                'value' => function ($model) {
                                    $potongan = $model['potongan_absensi'] ?? 0;
                                    $karyawanNama = $model['nama'] ?? 'Karyawan';
                                    $karyawanBagian = $model['nama_bagian'] ?? '-';
                                    $total_alfa = $model['total_alfa_range'] ?? 0;
                                    $gaji_perhari = $model['gaji_perhari'] ?? 0; // tambahkan ini
                                    $jumlah_wfh = $model['jumlah_wfh'] ?? 0;

                                    return '
            <button type="button" 
                    class="p-0 btn btn-link text-danger text-decoration-none btn-potongan-modal"
                    data-bs-toggle="modal" 
                    data-bs-target="#modalPotonganAbsensi"
                    onclick="loadPotonganAbsensiData(' . $model['id_karyawan'] . ', \'' . addslashes($karyawanNama) . '\', \'' . addslashes($karyawanBagian) . '\', ' . $total_alfa . ', ' . $gaji_perhari . ' , ' . $jumlah_wfh . ')"
                    title="Lihat Detail Potongan absensi">
                <span class="fw-semibold">Rp ' . number_format($potongan, 0, ',', '.') . '</span>
            </button>
        ';
                                }
                            ],

                            [
                                'attribute' => 'potongan_terlambat',
                                'label' => "Potongan Terlambat",
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'text-end align-middle'],
                                'contentOptions' => ['class' => 'text-end align-middle'],
                                'value' => function ($model) {

                                    $potongan = $model['potongan_terlambat'] ?? 0;
                                    $karyawanNama = $model['nama'] ?? 'Karyawan';
                                    $karyawanBagian = $model['nama_bagian'] ?? '-';

                                    return '
            <button type="button" 
                    class="p-0 btn btn-link text-danger text-decoration-none btn-tunjangan-modal"
                    data-bs-toggle="modal" 
                    data-bs-target="#modalPotonganTerlambat"
                    onclick="loadPotonganTerlambatData(' . $model['id_karyawan'] . ', \'' . addslashes($karyawanNama) . '\', \'' . addslashes($karyawanBagian) . '\')"
                    title="Lihat Detail potongan terlambat">
                <span class="fw-semibold">Rp ' . number_format($potongan, 0, ',', '.') . '</span>
            </button>
        ';
                                }
                            ],




                            [
                                'attribute' => 'jam_lembur',
                                'label' => "Total Lembur",
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'text-end align-middle'],
                                'contentOptions' => ['class' => 'text-end align-middle'],
                                'value' => function ($model) {
                                    $lembur = $model['total_pendapatan_lembur'] ?? 0;
                                    $karyawanNama = $model['nama'] ?? 'Karyawan';
                                    $karyawanBagian = $model['nama_bagian'] ?? '-';
                                    $bulan = $model['bulan'] ?? '';
                                    $tahun = $model['tahun'] ?? '';

                                    return '
            <button type="button" 
                    class="p-0 btn btn-link text-success text-decoration-none btn-tunjangan-modal"
                    data-bs-toggle="modal" 
                    data-bs-target="#modalLembur"
                    onclick="loadLemburData(' . $model['id_karyawan'] . ', \'' . addslashes($karyawanNama) . '\', \'' . addslashes($karyawanBagian) . '\', \'' . addslashes($bulan) . '\', \'' . addslashes($tahun) . '\')"
                    title="Lihat Detail lembur">
                <span class="fw-semibold">Rp ' . number_format($lembur, 0, ',', '.') . '</span>
            </button>
        ';
                                }
                            ],


                            [
                                'attribute' => 'dinas_luar_belum_terbayar',
                                'label' => "Dinas Luar Belum Terbayar",
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'text-end align-middle'],
                                'contentOptions' => ['class' => 'text-end align-middle'],
                                'value' => function ($model) {
                                    $dinas = $model['dinas_luar_belum_terbayar'] ?? 0;
                                    $karyawanNama = $model['nama'] ?? 'Karyawan';
                                    $karyawanBagian = $model['nama_bagian'] ?? '-';
                                    $bulan = $model['bulan'] ?? '';
                                    $tahun = $model['tahun'] ?? '';

                                    return '
            <button type="button" 
                    class="p-0 btn btn-link text-success text-decoration-none btn-tunjangan-modal"
                    data-bs-toggle="modal" 
                    data-bs-target="#modalDinas"
                    onclick="loadDinasData(' . $model['id_karyawan'] . ', \'' . addslashes($karyawanNama) . '\', \'' . addslashes($karyawanBagian) . '\', \'' . addslashes($bulan) . '\', \'' . addslashes($tahun) . '\')"
                    title="Lihat Detail Dinas Luar">
                <span class="fw-semibold">Rp ' . number_format($dinas, 0, ',', '.') . '</span>
            </button>
        ';
                                }
                            ],

                            [
                                'label' => "Gaji Diterima",
                                'format' => 'raw',
                                'attribute' => 'gaji_bersih',
                                'headerOptions' => ['class' => 'text-end align-middle'],
                                'contentOptions' => ['class' => 'text-end align-middle'],
                                'value' => function ($model) {
                                    $gajiBersih = $model['gaji_bersih'] ?? 0;
                                    $status = $gajiBersih > 0 ? 'success' : 'danger';
                                    return '<span class="text-' . $status . ' fw-bold">Rp ' . number_format($gajiBersih, 0, ',', '.') . '</span>';
                                }
                            ],

                            [
                                'attribute' => 'status',
                                'label' => "Status",
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'text-center align-middle'],
                                'contentOptions' => ['class' => 'text-center align-middle'],
                                'value' => function ($model) {

                                    if ($model['status'] == 1) {
                                        return '<span class="badge bg-success"><i class="fas fa-check me-1"></i> Sudah Diproses</span>';
                                    } else {
                                        return '<span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Belum Diproses</span>';
                                    }
                                }
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>


    <!-- Di bagian bawah file index.php, sebelum script -->


    <!-- Modal Konfirmasi -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Aksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 text-center">
                        <i class="fas fa-exclamation-triangle text-warning fa-3x"></i>
                    </div>
                    <p id="confirmMessage" class="text-center"></p>
                    <div class="mt-3 alert alert-primary">
                        <small>
                            <i class="fas fa-info-circle me-1"></i>
                            <strong>Karyawan:</strong> <span id="karyawanName"></span>
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="reset-button" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <button type="button" class="add-button" id="confirmAction">
                        <i class="fas fa-check me-1"></i>Ya, Lanjutkan
                    </button>
                </div>
            </div>
        </div>
    </div>




    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil elemen input dengan ID tanggal-input
            var tanggalInput = document.getElementById('tanggal-input');
            var bagian = document.getElementById('bagian-input');

            // Tambahkan event listener untuk perubahan

            function autosubmit() {
                var form = tanggalInput.closest('form');

                // Kirim form menggunakan metode GET
                if (form) {
                    setTimeout(function() {
                        form.submit();
                    }, 1500);
                }
            }

        });

        // Variabel untuk menyimpan URL aksi
        let currentActionUrl = '';

        // Event listener untuk tombol aksi
        document.addEventListener('DOMContentLoaded', function() {
            const confirmModal = document.getElementById('confirmModal');

            // Event ketika modal ditampilkan
            confirmModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const action = button.getAttribute('data-action');
                const url = button.getAttribute('data-url');
                const karyawan = button.getAttribute('data-karyawan');

                // Simpan URL untuk digunakan nanti
                currentActionUrl = url;

                // Set nama karyawan
                document.getElementById('karyawanName').textContent = karyawan;

                // Set pesan konfirmasi berdasarkan aksi
                let message = '';
                let modalTitle = '';

                switch (action) {
                    case 'process':
                        modalTitle = 'Proses Gaji';
                        message = 'Apakah Anda yakin ingin memproses gaji untuk karyawan ini?';
                        break;

                    case 'detail':
                        modalTitle = 'Lihat Detail Gaji';
                        message = 'Apakah Anda yakin ingin melihat detail transaksi gaji ini?';
                        break;

                    case 'regenerate':
                        modalTitle = 'Regenerate Gaji';
                        message = 'Apakah Anda yakin ingin meregenerasi transaksi gaji ini?<br><small class=\"text-danger\">Tindakan ini akan menghapus data yang sudah ada dan membuat yang baru.</small>';
                        break;
                }

                document.getElementById('confirmModalLabel').textContent = modalTitle;
                document.getElementById('confirmMessage').innerHTML = message;
            });

            // Event untuk tombol konfirmasi
            document.getElementById('confirmAction').addEventListener('click', function() {
                if (currentActionUrl) {
                    window.location.href = currentActionUrl;
                }
            });

            // Reset ketika modal ditutup
            confirmModal.addEventListener('hidden.bs.modal', function() {
                currentActionUrl = '';
            });
        });





        document.getElementById('generate-gaji-form').addEventListener('submit', function(e) {
            let confirmGenerate = confirm("Anda yakin akan lock data ini?");
            if (!confirmGenerate) {
                e.preventDefault();
            }
        });
    </script>