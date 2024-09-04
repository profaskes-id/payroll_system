<?php

use yii\helpers\Html;
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #131133 !important;">

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center justify-content-center">
            <div class="image">
                <img src="<?= $assetDir ?>/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle " style="width: 40px; opacity: .8">
            </div>
            <div class="info">
                <a href="#" style="font-size: 17.8px;" class="d-block fw-bold  text-white">Profaskes</a>
                <a href="#" style="font-size:14px" class="d-block">Payroll System</a>
            </div>
        </div>

        <div class="d-flex justify-content-center align-items-center w-100 mx-auto px-5">
            <?= Html::a('<i class="fa fa-solid fa-user"></i>', ['/user/profile'], ['class' => 'nav-link']) ?>
            <?= Html::a('<i class="fa fa-solid fa-cog"></i>', ['/user/account'], ['class' => 'nav-link']) ?>
            <?php if (Yii::$app->user->can('super_admin')) : ?>
                <?= Html::a('<i class="fa fa-solid fa-users"></i>', ['/user/admin'], ['class' => 'nav-link']) ?>
            <?php endif; ?>
            <?= Html::a('<i class="fas fa-sign-out-alt"></i>', ['/user/logout'], ['data-method' => 'post', 'class' => 'nav-link']) ?>

        </div>
        <hr style="background-color: white; margin: 0; padding: 0;">
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
        <div class="mt-2 d-flex justify-center items-end flex-column ">
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
                                    ['label' => 'Master Cuti',  'icon' => 'file-code', 'url' => ['/master-cuti/index'],],
                                ],
                            ],
                            [
                                'label' => 'Pengaturan Data',
                                'icon' => 'th',
                                'items' => [
                                    ['label' => 'Perusahaan',  'icon' => 'file-code', 'url' => ['/perusahaan/index'],],
                                    ['label' => 'Karyawan',  'icon' => 'file-code', 'url' => ['/karyawan/index'],],
                                ],
                            ],
                            [
                                'label' => 'Pengaturan Jam',
                                'icon' => 'th',
                                'items' => [
                                    ['label' => 'Jam Kerja',  'icon' => 'file-code', 'url' => ['/jam-kerja/index'],],
                                    ['label' => 'Jam Kerja Karyawan',  'icon' => 'file-code', 'url' => ['/jam-kerja-karyawan/index'],],
                                    ['label' => 'Hari Libur',  'icon' => 'file-code', 'url' => ['/hari-libur/index'],],
                                ],
                            ],
                            [
                                'label' => 'Pengajuan',
                                'icon' => 'th',
                                'items' => [
                                    ['label' => 'Pengajuan Cuti',  'icon' => 'file-code', 'url' => ['/pengajuan-cuti/index'],],
                                    ['label' => 'Pengajuan lembur',  'icon' => 'file-code', 'url' => ['/pengajuan-lembur/index'],],
                                    ['label' => 'Pengajuan Dinas',  'icon' => 'file-code', 'url' => ['/pengajuan-dinas/index'],],
                                    ['label' => 'Rekap Cuti',  'icon' => 'file-code', 'url' => ['/rekap-cuti/index'],],
                                ],
                            ],
                            ['label' => 'Absensi',  'icon' => 'file-code', 'url' => ['/absensi/index'],],
                            ['label' => 'Rekap Absensi',  'icon' => 'file-code', 'url' => ['/rekap-absensi/index'],],
                            ['label' => 'Pengumuman',  'icon' => 'file-code', 'url' => ['/pengumuman/index'],],
                        ],
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
                                    ['label' => 'Perusahaan',  'icon' => 'file-code', 'url' => ['/perusahaan/index'],],
                                    ['label' => 'Karyawan',  'icon' => 'file-code', 'url' => ['/karyawan/index'],],
                                ],
                            ],
                            [
                                'label' => 'Pengaturan Jam',
                                'icon' => 'th',
                                'items' => [
                                    ['label' => 'Jam Kerja',  'icon' => 'file-code', 'url' => ['/jam-kerja/index'],],
                                    ['label' => 'Jam Kerja Karyawan',  'icon' => 'file-code', 'url' => ['/jam-kerja-karyawan/index'],],
                                    ['label' => 'Hari Libur',  'icon' => 'file-code', 'url' => ['/hari-libur/index'],],
                                ],
                            ],
                            [
                                'label' => 'Pengajuan',
                                'icon' => 'th',
                                'items' => [
                                    ['label' => 'Pengajuan Cuti',  'icon' => 'file-code', 'url' => ['/pengajuan-cuti/index'],],
                                    ['label' => 'Pengajuan lembur',  'icon' => 'file-code', 'url' => ['/pengajuan-lembur/index'],],
                                    ['label' => 'Pengajuan Dinas',  'icon' => 'file-code', 'url' => ['/pengajuan-dinas/index'],],
                                ],
                            ],
                            ['label' => 'Absensi',  'icon' => 'file-code', 'url' => ['/absensi/index'],],
                            ['label' => 'Rekap Absensi',  'icon' => 'file-code', 'url' => ['/rekap-absensi/index'],],
                            ['label' => 'Pengumuman',  'icon' => 'file-code', 'url' => ['/pengumuman/index'],],
                        ],
                    ]);
                    ?>
                </nav>
            <?php endif; ?>



        </div>

        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>