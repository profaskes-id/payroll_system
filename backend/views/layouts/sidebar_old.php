<?php

use backend\models\IzinPulangCepat;
use backend\models\SettinganUmum;
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
                                'label' => 'Pengaturan Umum',
                                'icon' => 'th',
                                'items' => [
                                    ['label' => 'Master Kode',  'icon' => 'file-code', 'url' => ['/master-kode/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Profile Perusahaan', 'icon' => 'fa fa-building', 'url' => ['/perusahaan/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Master Lokasi',  'icon' => 'fa fa-map-marker', 'url' => ['/master-lokasi/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Master Hari Libur',  'icon' => 'fa fa-calendar-day', 'url' => ['/master-haribesar/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Master Cuti',  'icon' => 'fa  fa-calendar-alt', 'url' => ['/master-cuti/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Setting Lainnya ',  'icon' => 'fa fa-cog', 'url' => ['/settingan-umum/index'], 'options' => ['class' => 'ml-3'],],
                                ],
                            ],
                            [
                                'label' => 'Pengaturan HR',
                                'icon' => 'th',
                                'items' => [
                                    ['label' => 'Setting User ',  'icon' => 'fa fa-cog', 'url' => ['/user/admin/index'], 'options' => ['class' => 'ml-3'],],

                                    ['label' => 'Master Jabatan', 'icon' => 'fa fa-user-tie', 'url' => ['/jabatan/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Data Karyawan', 'icon' => 'fa fa-users', 'url' => ['/karyawan/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Setting Atasan dan Penempatan', 'icon' => 'fa fa-handshake', 'url' => ['/atasan-karyawan/index'], 'options' => ['class' => 'ml-3'],],
                                ],
                            ],
                            [
                                'label' => 'Pengaturan Jam Kerja',
                                'icon' => 'fa fa-hourglass-start',
                                'items' => [

                                    ['label' => 'Master Hari Kerja', 'icon' => 'fa fa-clock', 'url' => ['/jam-kerja/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Setting Jam Kerja ', 'icon' => 'fa fa-user-clock', 'url' => ['/jam-kerja-karyawan/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Setting Jadwal Shift ', 'icon' => 'fa fa-calendar-check', 'url' => ['/jadwal-shift/index'], 'options' => ['class' => 'ml-3'], 'visible' => SettinganUmum::find()->where(['kode_setting' => 'manual_shift'])->one()['nilai_setting'] == 1],
                                ],
                            ],
                            ['label' => 'Data Absensi',  'icon' => 'fa fa-user-check', 'url' => ['/absensi/index'],],


                            [
                                'label' => 'Penggajian',
                                'icon' => 'th',
                                'items' => [
                                    ['label' => 'Setting Periode Gaji',  'icon' => 'fa fa-calendar-check', 'url' => ['/periode-gaji/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Setting Tunjangan ',  'icon' => 'fa fa-user-tie', 'url' => ['/tunjangan-detail/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Setting Potongan',  'icon' => 'fa fa-user-slash', 'url' => ['/potongan-detail/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Transaksi Gaji',  'icon' => 'fa fa-exchange-alt', 'url' => ['/transaksi-gaji/index'], 'options' => ['class' => 'ml-3'],],

                                ],
                            ],
                            [
                                'label' => 'Expenses',
                                'icon' => 'th',
                                'items' => [
                                    ['label' => 'Kategori & Sub Kategori',  'icon' => 'fa fa-building', 'url' => ['/kategori-expenses/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Biaya & Beban',  'icon' => 'fa fa-users', 'url' => ['/expenses-detail/index'], 'options' => ['class' => 'ml-3'],],
                                ],
                            ],
                            [
                                'label' => 'Data Pengajuan',
                                'icon' => 'th',
                                'items' => [
                                    ['label' => 'Pengajuan WFH',  'icon' => 'fa fa-laptop-house', 'url' => ['/pengajuan-wfh/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Pengajuan Cuti',  'icon' => 'fa fa-paper-plane', 'url' => ['/pengajuan-cuti/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Pengajuan lembur',  'icon' => 'fa fa-hourglass', 'url' => ['/pengajuan-lembur/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Pengajuan Dinas',  'icon' => 'fa fa-map-marker-alt', 'url' => ['/pengajuan-dinas/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Pengajuan Shift',  'icon' => 'fa fa fa-briefcase', 'url' => ['/pengajuan-shift/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Pengajuan Pulang Cepat',  'icon' => 'fa fa fa-briefcase', 'url' => ['/izin-pulang-cepat/index'], 'options' => ['class' => 'ml-3'],],
                                ],
                            ],
                            [
                                'label' => 'Rekapitulasi Data',
                                'icon' => 'th',
                                'items' => [
                                    ['label' => 'Rekap Absensi',  'icon' => 'fa fa-table', 'url' => ['/rekap-absensi/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Rekap Cuti',  'icon' => 'fas fa-clipboard-list', 'url' => ['/rekap-cuti/index'], 'options' => ['class' => 'ml-3'],],
                                ],
                            ],
                            [
                                'label' => 'Lainnya',
                                'icon' => 'th',
                                'items' => [
                                    ['label' => 'faq',  'icon' => 'fas fa-question-circle', 'url' => ['/faq/index'], 'options' => ['class' => 'ml-3'],],

                                    ['label' => 'Pengumuman',  'icon' => 'fa fa-bullhorn', 'url' => ['/pengumuman/index'], 'options' => ['class' => 'ml-3'],],

                                    ['label' => 'Download Mobile App',  'icon' => 'fa fa-mobile ', 'url' => ['/download/index'], 'options' => ['class' => 'ml-3'],],

                                ],
                            ],


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
                                'label' => 'Pengaturan Umum',
                                'icon' => 'th',
                                'items' => [
                                    ['label' => 'Profile Perusahaan', 'icon' => 'fa fa-building', 'url' => ['/perusahaan/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Master Lokasi',  'icon' => 'fa fa-map-marker', 'url' => ['/master-lokasi/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Master Hari Libur',  'icon' => 'fa fa-calendar-day', 'url' => ['/master-haribesar/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Master Cuti',  'icon' => 'fa  fa-calendar-alt', 'url' => ['/master-cuti/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Setting Lainnya ',  'icon' => 'fa fa-cog', 'url' => ['/settingan-umum/index'], 'options' => ['class' => 'ml-3'],],
                                ],
                            ],
                            [
                                'label' => 'Pengaturan HR',
                                'icon' => 'th',
                                'items' => [
                                    ['label' => 'Setting User ',  'icon' => 'fa fa-cog', 'url' => ['/user/admin/index'], 'options' => ['class' => 'ml-3'],],

                                    ['label' => 'Master Jabatan', 'icon' => 'fa fa-user-tie', 'url' => ['/jabatan/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Data Karyawan', 'icon' => 'fa fa-users', 'url' => ['/karyawan/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Setting Atasan dan Penempatan', 'icon' => 'fa fa-handshake', 'url' => ['/atasan-karyawan/index'], 'options' => ['class' => 'ml-3'],],
                                ],
                            ],
                            [
                                'label' => 'Pengaturan Jam Kerja',
                                'icon' => 'fa fa-hourglass-start',
                                'items' => [

                                    ['label' => 'Master Hari Kerja', 'icon' => 'fa fa-clock', 'url' => ['/jam-kerja/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Setting Jam Kerja ', 'icon' => 'fa fa-user-clock', 'url' => ['/jam-kerja-karyawan/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Setting Jadwal Shift ', 'icon' => 'fa fa-calendar-check', 'url' => ['/jadwal-shift/index'], 'options' => ['class' => 'ml-3'], 'visible' => SettinganUmum::find()->where(['kode_setting' => 'manual_shift'])->one()['nilai_setting'] == 1],
                                ],
                            ],
                            ['label' => 'Data Absensi',  'icon' => 'fa fa-user-check', 'url' => ['/absensi/index'],],


                            [
                                'label' => 'Penggajian',
                                'icon' => 'th',
                                'items' => [
                                    ['label' => 'Setting Periode Gaji',  'icon' => 'fa fa-calendar-check', 'url' => ['/periode-gaji/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Setting Tunjangan ',  'icon' => 'fa fa-user-tie', 'url' => ['/tunjangan-detail/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Setting Potongan',  'icon' => 'fa fa-user-slash', 'url' => ['/potongan-detail/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Transaksi Gaji',  'icon' => 'fa fa-exchange-alt', 'url' => ['/transaksi-gaji/index'], 'options' => ['class' => 'ml-3'],],

                                ],
                            ],
                            [
                                'label' => 'Expenses',
                                'icon' => 'th',
                                'items' => [
                                    ['label' => 'Kategori & Sub Kategori',  'icon' => 'fa fa-building', 'url' => ['/kategori-expenses/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Biaya & Beban',  'icon' => 'fa fa-users', 'url' => ['/expenses-detail/index'], 'options' => ['class' => 'ml-3'],],
                                ],
                            ],
                            [
                                'label' => 'Data Pengajuan',
                                'icon' => 'th',
                                'items' => [
                                    ['label' => 'Pengajuan WFH',  'icon' => 'fa fa-laptop-house', 'url' => ['/pengajuan-wfh/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Pengajuan Cuti',  'icon' => 'fa fa-paper-plane', 'url' => ['/pengajuan-cuti/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Pengajuan lembur',  'icon' => 'fa fa-hourglass', 'url' => ['/pengajuan-lembur/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Pengajuan Dinas',  'icon' => 'fa fa-map-marker-alt', 'url' => ['/pengajuan-dinas/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Pengajuan Shift',  'icon' => 'fa fa fa-briefcase', 'url' => ['/pengajuan-shift/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Pengajuan Pulang Cepat',  'icon' => 'fa fa fa-briefcase', 'url' => ['/izin-pulang-cepat/index'], 'options' => ['class' => 'ml-3'],],
                                ],
                            ],
                            [
                                'label' => 'Rekapitulasi Data',
                                'icon' => 'th',
                                'items' => [
                                    ['label' => 'Rekap Absensi',  'icon' => 'fa fa-table', 'url' => ['/rekap-absensi/index'], 'options' => ['class' => 'ml-3'],],
                                    ['label' => 'Rekap Cuti',  'icon' => 'fas fa-clipboard-list', 'url' => ['/rekap-cuti/index'], 'options' => ['class' => 'ml-3'],],
                                ],
                            ],
                            [
                                'label' => 'Lainnya',
                                'icon' => 'th',
                                'items' => [
                                    ['label' => 'faq',  'icon' => 'fas fa-question-circle', 'url' => ['/faq/index'], 'options' => ['class' => 'ml-3'],],

                                    ['label' => 'Pengumuman',  'icon' => 'fa fa-bullhorn', 'url' => ['/pengumuman/index'], 'options' => ['class' => 'ml-3'],],

                                    ['label' => 'Download Mobile App',  'icon' => 'fa fa-mobile ', 'url' => ['/download/index'], 'options' => ['class' => 'ml-3'],],

                                ],
                            ],


                        ],
                        'encodeLabels' => false, // Pastikan ini diatur ke false agar HTML di-render
                    ]);
                    ?>
                </nav>
            <?php endif; ?>

        </div>
    </div>
</aside>