    <?php

    use backend\models\helpers\KaryawanHelper;
    use backend\models\PendingKasbon;
    use yii\helpers\Html;

    use yii\grid\GridView;


    $karyawan = new KaryawanHelper();


    $this->title = Yii::t('app', 'Transaksi Gaji');
    $this->params['breadcrumbs'][] = $this->title;

    ?>
    <style>
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

        /* Karyawan info */
        .karyawan-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Position badge */
        .position-badge {
            min-width: 140px;
        }

        /* Button small styling */
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
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap 5 (Bundle = termasuk Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <?php
    // Include semua modal
    echo $this->render('detail/_modal_kasbon');
    echo $this->render('detail/_modal_tunjangan');
    echo $this->render('detail/_modal_potongan');
    echo $this->render('detail/_modal_potongan_absensi');
    echo $this->render('detail/_modal_potongan_terlambat');
    echo $this->render('detail/_modal_dinas');
    echo $this->render('detail/_modal_lembur');
    echo $this->render('detail/_modal_pendapatan_lainnya');
    echo $this->render('detail/_modal_potongan_lainnya');
    // dd($karyawanID);
    ?>



    <button style="width: 100%;" class="add-button" type="submit" data-toggle="collapse" data-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample">
        <i class="fas fa-search"></i>
        <span>
            Search
        </span>
    </button>
    <div style="margin-top: 10px;">
        <div class="collapse width" id="collapseWidthExample">
            <div class="" style="width: 100%;">
                <?= $this->render('_search', ['model' => $model, 'karyawanID' => $karyawanID, 'id_bagian' => $id_bagian, 'jabatan' => $jabatan, 'status_pekerjaan' => $status_pekerjaan]) ?>
            </div>
        </div>
    </div>


    <div class="transaksi-gaji-index">
        <div class="card">
            <div class="bg-white card-header">
                <div class="d-flex justify-content-between align-items-center">


                    <div class="flex-wrap btn-group">
                        <?= Html::beginForm(['transaksi-gaji/generate-gaji'], 'post', ['id' => 'generate-gaji-form']); ?>
                        <?= Html::hiddenInput('karyawanID', $karyawanID) ?>
                        <?= Html::hiddenInput('bulan', Yii::$app->request->get('TransaksiGaji') ? Yii::$app->request->get('TransaksiGaji')['bulan'] : date('m'));  ?>
                        <?= Html::hiddenInput('tahun', Yii::$app->request->get('TransaksiGaji') ? Yii::$app->request->get('TransaksiGaji')['tahun'] : date('Y'));  ?>
                        <?= Html::submitButton('Generate Gaji', [
                            'class' => 'add-button',
                            'id' => 'generate-gaji-button'
                        ]) ?>
                        <?= Html::endForm(); ?>


                        <a target="_blank" href="/panel/transaksi-gaji/report?bulan=<?= Yii::$app->request->get('TransaksiGaji')['bulan'] ?? date('m') ?>&tahun=<?= Yii::$app->request->get('TransaksiGaji')['tahun'] ?? date('Y') ?>" class="mx-2 reset-button bg-warning">
                            <i class="fas fa-print me-1"></i> Cetak Transaksi
                        </a>


                        <?= Html::beginForm(['transaksi-gaji/delete-all-data'], 'post', ['id' => 'delete-all-gaji-form']); ?>
                        <?= Html::hiddenInput('karyawanID', $karyawanID) ?>
                        <?= Html::hiddenInput('bulan', Yii::$app->request->get('TransaksiGaji') ? Yii::$app->request->get('TransaksiGaji')['bulan'] : date('m')); ?>
                        <?= Html::hiddenInput('tahun', Yii::$app->request->get('TransaksiGaji') ? Yii::$app->request->get('TransaksiGaji')['tahun'] : date('Y')); ?>

                        <?= Html::submitButton('Delete All', [
                            'class' => 'reset-button bg-danger',
                            'id' => 'delete-all-gaji-button',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete all salaries for this period?',
                                'method' => 'post',
                            ],
                        ]) ?>
                        <?= Html::endForm(); ?>
                    </div>

                    <h5 class="mb-0 card-title">
                        <i class="fas fa-money-bill-wave me-2"></i>
                        <?= Html::encode($this->title) ?>
                    </h5>

                </div>
            </div>

            <div class="p-0 card-body">
                <div class="table-responsive" style="max-height: 85vh;">
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
                                    'style' => 'width: 120px;'
                                ],
                                'contentOptions' => ['class' => 'text-center align-middle'],
                                'format' => 'raw',
                                'value' => function ($model) {
                                    $bulan = Yii::$app->request->get('TransaksiGaji') ? Yii::$app->request->get('TransaksiGaji')['bulan'] : date('m');
                                    $tahun = Yii::$app->request->get('TransaksiGaji') ? Yii::$app->request->get('TransaksiGaji')['tahun'] : date('Y');

                                    $buttons = '<div class="gap-1 d-flex justify-content-center">';
                                    if (isset($model['id_transaksi_gaji']) && $model['id_transaksi_gaji'] != null) {
                                        // Tombol Delete
                                        $buttons .= Html::a(
                                            '<i class="fas fa-trash"></i>',
                                            ['deleterow', 'id_karyawan' => $model['id_karyawan'], 'bulan' => $model['bulan'], 'tahun' => $model['tahun']],
                                            [
                                                'class' => 'btn btn-danger btn-sm sweet-confirm',
                                                'data-title' => 'Konfirmasi Hapus Gaji',
                                                'data-text' => 'Apakah Anda yakin ingin menghapus gaji untuk ' . Html::encode($model['nama'] ?? 'Karyawan') . '?',
                                                'data-confirm-button' => 'Ya, Hapus!',
                                                'data-cancel-button' => 'Batal',
                                                'title' => 'Delete Gaji',
                                                'data-bs-toggle' => 'tooltip',
                                                'data-bs-placement' => 'top'
                                            ]
                                        );

                                        // Tombol Regenerate
                                        $buttons .= Html::a(
                                            '<i class="fas fa-sync-alt"></i>',
                                            ['generate-gaji-one', 'id_karyawan' => $model['id_karyawan'], 'bulan' => $model['bulan'], 'tahun' => $model['tahun']],
                                            [
                                                'class' => 'btn btn-warning btn-sm sweet-confirm',
                                                'data-title' => 'Konfirmasi Regenerate Gaji',
                                                'data-text' => 'Apakah Anda yakin ingin regenerate gaji untuk ' . Html::encode($model['nama'] ?? 'Karyawan') . '?',
                                                'data-confirm-button' => 'Ya, Regenerate!',
                                                'data-cancel-button' => 'Batal',
                                                'title' => 'Regenerate Gaji',
                                                'data-bs-toggle' => 'tooltip',
                                                'data-bs-placement' => 'top'
                                            ]
                                        );

                                        // Tombol Cetak
                                        $buttons .= Html::a(
                                            '<i class="fas fa-print"></i>',
                                            ['slip-gaji-pdf', 'id_transaksi_gaji' => $model['id_transaksi_gaji'], 'id_karyawan' => $model['id_karyawan']],
                                            [
                                                'class' => 'btn btn-primary btn-sm',
                                                'title' => 'Cetak Slip Gaji',
                                                'data-bs-toggle' => 'tooltip',
                                                'data-bs-placement' => 'top'
                                            ]
                                        );

                                        // Tombol Email
                                        $buttons .= Html::a(
                                            '<i class="fas fa-envelope"></i>',
                                            ['email-gaji', 'id_transaksi_gaji' => $model['id_transaksi_gaji'], 'id_karyawan' => $model['id_karyawan']],
                                            [
                                                'class' => 'btn btn-info btn-sm',
                                                'title' => 'Kirim Email Gaji',
                                                'data-bs-toggle' => 'tooltip',
                                                'data-bs-placement' => 'top'
                                            ]
                                        );
                                    } else {
                                        // Tombol Proses Gaji
                                        $buttons .= Html::a(
                                            '<i class="fas fa-lock"></i>',
                                            ['generate-gaji-one', 'id_karyawan' => $model['id_karyawan'], 'bulan' => $bulan, 'tahun' => $tahun],
                                            [
                                                'class' => 'btn btn-success btn-sm sweet-confirm',
                                                'data-title' => 'Konfirmasi Proses Gaji',
                                                'data-text' => 'Apakah Anda yakin ingin memproses gaji untuk ' . Html::encode($model['nama'] ?? 'Karyawan') . '?',
                                                'data-confirm-button' => 'Ya, Proses!',
                                                'data-cancel-button' => 'Batal',
                                                'title' => 'Proses Gaji',
                                                'data-bs-toggle' => 'tooltip',
                                                'data-bs-placement' => 'top'
                                            ]
                                        );
                                    }

                                    $buttons .= '</div>';
                                    return $buttons;
                                }
                            ],

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
                                'label' => "Bagian & Jabatan & status",
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'align-middle', 'style' => 'width: 200px;'],
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
                                    $status_pekerjaan = $model['status_pekerjaan'] ?? '-';
                                    return '
            <div>
                <div class="fw-bold text-dark small">' . Html::encode($bagian) . '</div>
                <hr class="mx-0 my-1" />
                <div class=" small">' . Html::encode($jabatan) . '</div>
                <hr class="mx-0 my-1" />
                <div class=" small">' . Html::encode($status_pekerjaan) . '</div>
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
                                'attribute' => 'pendapatan_lainnya',
                                'label' => "Pendapatan Lainnya",
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'text-end align-middle'],
                                'contentOptions' => ['class' => 'text-end align-middle'],
                                'value' => function ($model) {

                                    $pendapatan = $model['pendapatan_lainnya'] ?? 0;
                                    $idKaryawan = $model['id_karyawan'] ?? '';
                                    $bulan      = $model['bulan'] ?? '';
                                    $tahun      = $model['tahun'] ?? '';

                                    return '
    <button type="button" 
            class="p-0 btn btn-link text-success text-decoration-none btn-pendapatanlainnya-modal"
            data-bs-toggle="modal" 
            data-bs-target="#modalPendapatanlainnya"
            onclick="loadPendapatanLainnyaData('
                                        . (int)$pendapatan . ', '
                                        . (int)$idKaryawan . ', \''
                                        . addslashes($bulan) . '\', \''
                                        . addslashes($tahun) . '\')"
            title="Lihat Detail Pendapatanlainnya">
        <span class="fw-semibold">Rp ' . number_format($pendapatan, 0, ',', '.') . '</span>
    </button>
';
                                }
                            ],

                            [
                                'attribute' => 'potongan_lainnya',
                                'label' => "Potongan Lainnya",
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'text-end align-middle'],
                                'contentOptions' => ['class' => 'text-end align-middle'],
                                'value' => function ($model) {

                                    $potongan     = $model['potongan_lainnya'] ?? 0;
                                    $idKaryawan   = $model['id_karyawan'] ?? '';
                                    $bulan        = $model['bulan'] ?? '';
                                    $tahun        = $model['tahun'] ?? '';

                                    return '
        <button type="button" 
                class="p-0 btn btn-link text-danger text-decoration-none btn-potonganlainnya-modal"
                data-bs-toggle="modal" 
                data-bs-target="#modalPotonganlainnya"
                onclick="loadPotonganLainnyaData('
                                        . (int)$potongan . ', '
                                        . (int)$idKaryawan . ', \''
                                        . addslashes($bulan) . '\', \''
                                        . addslashes($tahun) . '\')"
                title="Lihat Detail Potongan Lainnya">
            <span class="fw-semibold">Rp ' . number_format($potongan, 0, ',', '.') . '</span>
        </button>
        ';
                                }
                            ],














                            [
                                'attribute' => 'kasbon_karyawan',
                                'label' => 'Bayar Kasbon',
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'text-end align-middle'],
                                'contentOptions' => ['class' => 'text-end align-middle'],
                                'value' => function ($model) {
                                    $akasbonKarywanini = PendingKasbon::find()->where([
                                        'id_karyawan' => $model['id_karyawan'],
                                        'bulan' => Yii::$app->request->get('TransaksiGaji')['bulan'] ?? date('m'),
                                        'tahun' => Yii::$app->request->get('TransaksiGaji')['tahun'] ?? date('Y')
                                    ])->exists();

                                    $potongan = $model['kasbon_karyawan'] ?? 0;
                                    $karyawanNama = $model['nama'] ?? 'Karyawan';
                                    $karyawanBagian = $model['nama_bagian'] ?? '-';

                                    return Html::button(
                                        $akasbonKarywanini
                                            ? '<span class="fw-semibold" style="background-color: transparent; padding: 2px 8px; border-radius: 4px;">Pending</span>'
                                            : '<span class="fw-semibold text-danger">Rp ' . number_format($potongan, 0, ',', '.') . '</span>',
                                        [
                                            'class' => $akasbonKarywanini ? 'p-0 btn btn-link text-decoration-none' : 'p-0 btn btn-link text-danger text-decoration-none',
                                            'data-bs-toggle' => 'modal',
                                            'data-bs-target' => '#modalKasbon',
                                            'onclick' => "loadKasbonData({$model['id_karyawan']}, '" . addslashes($karyawanNama) . "', '" . addslashes($karyawanBagian) . "')",
                                            'title' => 'Lihat Detail Kasbon',
                                            'data-bs-placement' => 'top'
                                        ]
                                    );
                                }
                            ],
                            [
                                'attribute' => 'hari_kerja_efektif',
                                'label' => 'Hari Kerja Efektif',
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'text-end align-middle'],
                                'contentOptions' => ['class' => 'text-end align-middle'],
                                'value' => function ($model) {
                                    return $model['hari_kerja_efektif'] ?? 0;
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

                                    return '
                <span class="fw-semibold text-danger">Rp ' . number_format($potongan, 0, ',', '.') . '</span>';
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
                                    // Jika gaji_diterima ada dan tidak null, pakai itu, kalau tidak pakai gaji_bersih
                                    $gajiBersih = isset($model['gaji_diterima']) && $model['gaji_diterima'] !== null
                                        ? $model['gaji_diterima']
                                        : ($model['gaji_bersih'] ?? 0); // fallback ke 0 kalau juga tidak ada

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
                                    // Pastikan status selalu punya nilai default (misal null dianggap belum diproses)
                                    $status = isset($model['status']) ? $model['status'] : null;

                                    if ($status == 1) {
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



    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi tooltip Bootstrap
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Tangani SweetAlert untuk tombol dengan class sweet-confirm
            document.querySelectorAll('.sweet-confirm').forEach(function(button) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    var url = this.getAttribute('href');
                    var title = this.getAttribute('data-title');
                    var text = this.getAttribute('data-text');
                    var confirmButton = this.getAttribute('data-confirm-button');
                    var cancelButton = this.getAttribute('data-cancel-button');

                    Swal.fire({
                        title: title,
                        text: text,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: confirmButton,
                        cancelButtonText: cancelButton
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = url;
                        }
                    });
                });
            });
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

            // Variabel untuk menyimpan URL aksi
            let currentActionUrl = '';

            // Event listener untuk tombol aksi

            document.getElementById('generate-gaji-form').addEventListener('submit', function(e) {
                let confirmGenerate = confirm("Anda yakin akan lock data ini?");
                if (!confirmGenerate) {
                    e.preventDefault();
                }
            });
        });
    </script>