<?php

use backend\models\IzinPulangCepat;
use yii\helpers\Html;
?>

<?php
$jumlahPulangCepatToday = IzinPulangCepat::find()->where(['tanggal' => date('Y-m-d'), 'status' => 0])->count();

?>

<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #131133 !important; ">

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="pb-3 mt-3 mb-3 user-panel d-flex align-items-center justify-content-center">
            <div class="image">
                <img src="<?= Yii::getAlias('@root') ?>/images/logo.svg" alt="Profaskes Logo" class="brand-image img-circle " style="width: 60px; ">
            </div>
            <div class="info">
                <a href="#" style="font-size: 17.8px;" class="text-white d-block fw-bold">Profaskes</a>
                <a href="#" style="font-size:14px" class="d-block">Payroll System</a>
            </div>
        </div>

        <div class="px-5 mx-auto d-flex justify-content-center align-items-center w-100">
            <?= Html::a('<i class="fa fa-solid fa-user"></i>', ['/user/profile'], ['class' => 'nav-link']) ?>
            <?= Html::a('<i class="fa fa-solid fa-cog"></i>', ['/user/account'], ['class' => 'nav-link']) ?>
            <?php // if (Yii::$app->user->can('super_admin')) : 
            ?>
            <?php // Html::a('<i class="fa fa-solid fa-users"></i>', ['/user/admin'], ['class' => 'nav-link']) 
            ?>
            <?php // endif; 
            ?>
            <?= Html::a('<i class="fas fa-sign-out-alt"></i>', ['/user/logout'], ['data-method' => 'post', 'class' => 'nav-link']) ?>
        </div>
        <hr style="background-color: white; margin: 0; padding: 0;" />
        <!-- SidebarSearch Form -->
        <!-- href be escaped -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <div class="items-end justify-center mt-2 d-flex flex-column ">
            <?php if (Yii::$app->user->can('super_admin')) : ?>
                <nav class="">
                    <?php
                    echo \hail812\adminlte\widgets\Menu::widget([
                        'items' => [
                            ['label' => 'Dashboard',  'icon' => 'home', 'url' => ['/']],

                            [
                                'label' => 'Pengaturan System',
                                'icon' => 'th',
                                'items' => [
                                    ['label' => 'Master Kode',  'icon' => 'file-code', 'url' => ['/master-kode/index'],],
                                    ['label' => 'Master Cuti',  'icon' => 'fa  fa-calendar-alt', 'url' => ['/master-cuti/index'],],
                                    ['label' => 'Master Hari Besar',  'icon' => 'fa fa-calendar-day', 'url' => ['/master-haribesar/index'],],
                                    ['label' => 'Master Lokasi',  'icon' => 'fa fa-map-marker', 'url' => ['/master-lokasi/index'],],
                                    ['label' => 'Master Gaji',  'icon' => 'fa fa-money-bill-alt', 'url' => ['/master-gaji/index'],],
                                    ['label' => 'Periode Gaji',  'icon' => 'fa fa-calendar-check', 'url' => ['/periode-gaji/index'],],
                                    ['label' => 'Total Hari Kerja',  'icon' => 'fas fa-briefcase', 'url' => ['/total-hari-kerja/index'],],
                                    ['label' => 'faq',  'icon' => 'fas fa-question-circle', 'url' => ['/faq/index'],],
                                ],
                            ],
                            [
                                'label' => 'Pengaturan Data',
                                'icon' => 'th',
                                'items' => [
                                    [ // Submenu: Pengaturan Karyawan
                                        'label' => 'Data Karyawan',
                                        'icon' => 'fa fa-users-cog',
                                        'items' => [
                                            ['label' => 'Profile Perusahaan', 'icon' => 'fa fa-building', 'url' => ['/perusahaan/index']],
                                            ['label' => 'Jabatan', 'icon' => 'fa fa-user-tie', 'url' => ['/jabatan/index']],
                                            ['label' => 'Karyawan', 'icon' => 'fa fa-users', 'url' => ['/karyawan/index']],
                                            [
                                                'label' => 'User',
                                                'icon' => 'fa fa-users',
                                                'url' => ['/user/admin'],
                                                'visible' => Yii::$app->user->can('super_admin'), // 👈 Kondisi ditambahkan di sini
                                            ],
                                            ['label' => 'Atasan dan Penempatan', 'icon' => 'fa fa-handshake', 'url' => ['/atasan-karyawan/index']],
                                        ],
                                    ],
                                    [ // Submenu: Pengaturan Jam
                                        'label' => 'Pengaturan Jam',
                                        'icon' => 'fa fa-clock',
                                        'items' => [
                                            ['label' => 'Jam Kerja', 'icon' => 'fa fa-clock', 'url' => ['/jam-kerja/index']],
                                            ['label' => 'Shift Kerja', 'icon' => 'fa fa-cogs', 'url' => ['/shift-kerja/index']],
                                            ['label' => 'Jam Kerja Karyawan', 'icon' => 'fa fa-user-clock', 'url' => ['/jam-kerja-karyawan/index']],
                                        ],
                                    ],

                                ],
                            ],

                            [
                                'label' => 'Transaksi',
                                'icon' => 'th',
                                'items' => [
                                    ['label' => 'Transaksi Gaji',  'icon' => 'fa fa-exchange-alt', 'url' => ['/transaksi-gaji/index'],],
                                    // ['label' => 'Tunjangan',  'icon' => 'fa fa-hand-holding-usd', 'url' => ['/tunjangan/index'],],
                                    ['label' => 'Tunjangan Karyawan',  'icon' => 'fa fa-user-tie', 'url' => ['/tunjangan-detail/index'],],
                                    // ['label' => 'Potongan',  'icon' => 'fa fa-minus-circle', 'url' => ['/potongan/index'],],
                                    ['label' => 'Potongan Karyawan',  'icon' => 'fa fa-user-slash', 'url' => ['/potongan-detail/index'],],
                                    // ['label' => 'Potongan Gaji',  'icon' => 'fa fa-dollar-sign', 'url' => ['/gaji-potongan/index'],],
                                    // ['label' => 'Tunjangan Gaji',  'icon' => 'fa fa-money-bill-wave', 'url' => ['/gaji-tunjangan/index'],],
                                    [
                                        'label' => 'Pengeluaran',
                                        'icon' => 'th',
                                        'items' => [
                                            ['label' => 'Kategori',  'icon' => 'fa fa-building', 'url' => ['/kategori-expenses/index'],],
                                            ['label' => 'Sub Kategori',  'icon' => 'fa fa-user-tie', 'url' => ['/subkategori-expenses/index'],],
                                            // ['label' => 'Header',  'icon' => 'fa fa-users', 'url' => ['/expenses-header/index'],],
                                            ['label' => 'Biaya & Beban',  'icon' => 'fa fa-users', 'url' => ['/expenses-detail/index'],],
                                        ],
                                    ],
                                ],
                            ],

                            ['label' => 'Absensi',  'icon' => 'fa fa-user-check', 'url' => ['/absensi/index'],],
                            [
                                'label' => 'Pengajuan',
                                'icon' => 'th',
                                'items' => [
                                    ['label' => 'Pengajuan WFH',  'icon' => 'fa fa-laptop-house', 'url' => ['/pengajuan-wfh/index'],],
                                    ['label' => 'Pengajuan Cuti',  'icon' => 'fa fa-paper-plane', 'url' => ['/pengajuan-cuti/index'],],
                                    ['label' => 'Pengajuan lembur',  'icon' => 'fa fa-hourglass', 'url' => ['/pengajuan-lembur/index'],],
                                    ['label' => 'Pengajuan Dinas',  'icon' => 'fa fa-map-marker-alt', 'url' => ['/pengajuan-dinas/index'],],
                                ],
                            ],
                            [
                                'label' => 'Rekapitulasi Data',
                                'icon' => 'th',
                                'items' => [
                                    ['label' => 'Rekap Absensi',  'icon' => 'fa fa-table', 'url' => ['/rekap-absensi/index'],],
                                    ['label' => 'Rekap Cuti',  'icon' => 'fas fa-clipboard-list', 'url' => ['/rekap-cuti/index'],],
                                    // ['label' => 'Rekap Lembur',  'icon' => 'fas fa-clipboard-list', 'url' => ['/rekap-lembur/index'],],
                                    // ['label' => 'Rekap dinas',  'icon' => 'fas fa-clipboard-list', 'url' => ['/rekap-dinas/index'],],
                                    // ['label' => 'Rekap WFH',  'icon' => 'fas fa-clipboard-list', 'url' => ['/rekap-wfh/index'],],
                                ],
                            ],
                            ['label' => 'Pengumuman',  'icon' => 'fa fa-bullhorn', 'url' => ['/pengumuman/index'],],
                            [
                                'label' => 'Pulang Cepat <span class="right badge badge-warning">' . $jumlahPulangCepatToday . '</span>',
                                'icon' => 'fas fa-clock',
                                'url' => ['/izin-pulang-cepat/index'],
                                'options' => ['class' => 'nav-item'], // Opsional, untuk styling
                            ],
                            ['label' => 'Download Mobile App',  'icon' => 'fa fa-mobile ', 'url' => ['/download/index'],],

                        ],
                        'encodeLabels' => false, // Pastikan ini diatur ke false agar HTML di-render
                    ]);
                    ?>
                </nav>
            <?php else : ?>
                <nav class="">
                    <?php
                    echo \hail812\adminlte\widgets\Menu::widget([
                        'items' => [
                            ['label' => 'Dashboard',  'icon' => 'home', 'url' => ['/']],
                            [
                                'label' => 'Pengaturan Data',
                                'icon' => 'th',
                                'items' => [
                                    ['label' => 'Profile Perusahaan',  'icon' => 'fa fa-building', 'url' => ['/perusahaan/index'],],
                                    ['label' => 'Jabatan',  'icon' => 'fa fa-user-tie', 'url' => ['/jabatan/index'],],
                                    ['label' => 'Karyawan',  'icon' => 'fa fa-users', 'url' => ['/karyawan/index'],],
                                    ['label' => 'Atasan dan Penempatan',  'icon' => 'fa fa-handshake', 'url' => ['/atasan-karyawan/index'],],
                                    ['label' => 'Jam Kerja',  'icon' => 'fa fa-clock', 'url' => ['/jam-kerja/index'],],
                                    ['label' => 'Shift Kerja',  'icon' => 'fa fa-cogs', 'url' => ['/shift-kerja/index'],],
                                    ['label' => 'Jam Kerja Karyawan',  'icon' => 'fa fa-user-clock', 'url' => ['/jam-kerja-karyawan/index'],],

                                ],
                            ],
                            ['label' => 'Absensi',  'icon' => 'fa fa-user-check', 'url' => ['/absensi/index'],],
                            [
                                'label' => 'Pengajuan',
                                'icon' => 'th',
                                'items' => [
                                    ['label' => 'Pengajuan WFH',  'icon' => 'fa fa-laptop-house', 'url' => ['/pengajuan-wfh/index'],],
                                    ['label' => 'Pengajuan Cuti',  'icon' => 'fa fa-paper-plane', 'url' => ['/pengajuan-cuti/index'],],
                                    ['label' => 'Pengajuan lembur',  'icon' => 'fa fa-hourglass', 'url' => ['/pengajuan-lembur/index'],],
                                    ['label' => 'Pengajuan Dinas',  'icon' => 'fa fa-map-marker-alt', 'url' => ['/pengajuan-dinas/index'],],
                                ],
                            ],
                            [
                                'label' => 'Rekapitulasi Data',
                                'icon' => 'th',
                                'items' => [
                                    ['label' => 'Rekap Absensi',  'icon' => 'fa fa-table', 'url' => ['/rekap-absensi/index'],],
                                    ['label' => 'Rekap Cuti',  'icon' => 'fas fa-clipboard-list', 'url' => ['/rekap-cuti/index'],],
                                    // ['label' => 'Rekap Lembur',  'icon' => 'fas fa-clipboard-list', 'url' => ['/rekap-lembur/index'],],
                                    // ['label' => 'Rekap dinas',  'icon' => 'fas fa-clipboard-list', 'url' => ['/rekap-dinas/index'],],
                                    // ['label' => 'Rekap WFH',  'icon' => 'fas fa-clipboard-list', 'url' => ['/rekap-wfh/index'],],
                                ],
                            ],
                            ['label' => 'Pengumuman',  'icon' => 'fa fa-bullhorn', 'url' => ['/pengumuman/index'],],
                            [
                                'label' => 'Pulang Cepat <span class="right badge badge-warning">' . $jumlahPulangCepatToday . '</span>',
                                'icon' => 'fas fa-clock',
                                'url' => ['/izin-pulang-cepat/index'],
                                'options' => ['class' => 'nav-item'], // Opsional, untuk styling
                            ],
                            ['label' => 'Download Mobile App',  'icon' => 'fa fa-mobile ', 'url' => ['/download/index'],],

                        ],
                        'encodeLabels' => false, // Pastikan ini diatur ke false agar HTML di-render
                    ]);
                    ?>
                </nav>
            <?php endif; ?>

        </div>
    </div>
</aside>