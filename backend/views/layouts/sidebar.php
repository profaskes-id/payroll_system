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
        <nav class="mt-2">
            <?php
            echo \hail812\adminlte\widgets\Menu::widget([
                'items' => [

                    // ['label' => 'Simple Link', 'icon' => 'th', 'badge' => '<span class="right badge badge-danger">New</span>'],
                    // ['label' => 'Yii2 PROVIDED', 'header' => true],
                    // ['label' => 'Gii',  'icon' => 'file-code', 'url' => ['/gii'], 'target' => '_blank'],

                    // [
                    // 'label' => 'Pengaturan Halaman',
                    // 'icon' => 'th',
                    // 'items' => [
                    // ['label' => 'Menu Settings', 'url' => ['/absensi/index'], 'iconStyle' => 'far'],
                    // ],
                    // ],
                    ['label' => 'Master Kode',  'icon' => 'file-code', 'url' => ['/master-kode/index'],],
                    ['label' => 'Karyawan',  'icon' => 'file-code', 'url' => ['/karyawan/index'],],
                    ['label' => 'Perusahaan',  'icon' => 'file-code', 'url' => ['/perusahaan/index'],],
                    ['label' => 'Bagian',  'icon' => 'file-code', 'url' => ['/bagian/index'],],
                    ['label' => 'Jam Kerja',  'icon' => 'file-code', 'url' => ['/jam-kerja/index'],],
                    ['label' => 'Absensi',  'icon' => 'file-code', 'url' => ['/absensi/index'],],
                    ['label' => 'Data Keluarga',  'icon' => 'file-code', 'url' => ['/data-keluarga/index'],],
                    ['label' => 'Data Pekerjaan',  'icon' => 'file-code', 'url' => ['/data-pekerjaan/index'],],
                    ['label' => 'Hari Libur',  'icon' => 'file-code', 'url' => ['/hari-libur/index'],],
                    ['label' => 'Jadwal Kerja',  'icon' => 'file-code', 'url' => ['/jadwal-kerja/index'],],
                    ['label' => 'Jam Kerja Karyawan',  'icon' => 'file-code', 'url' => ['/jam-kerja-karyawan/index'],],
                    ['label' => 'Pengalaman Kerja',  'icon' => 'file-code', 'url' => ['/pengalaman-kerja/index'],],
                    ['label' => 'riwayat pendidikan',  'icon' => 'file-code', 'url' => ['/riwayat-pendidikan/index'],],


                    // ['label' => 'Login', 'url' => ['site/login'], 'icon' => 'sign-in-alt', 'visible' => Yii::$app->user->isGuest],

                ],
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>