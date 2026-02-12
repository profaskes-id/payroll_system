<?php

use yii\helpers\Html;
use yii\web\View;

?>



<div class="container relative z-40 px-4 py-8 mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="mb-2 text-3xl font-bold text-gray-800">Informasi Karyawan</h1>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        <!-- Card Cuti -->
        <div class="p-6 bg-white border-l-4 border-blue-500 shadow-lg rounded-xl">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Cuti</h2>
                    <p class="text-sm text-gray-500">Jatah cuti tersedia</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="text-xl text-blue-600 fas fa-calendar-alt"></i>
                </div>
            </div>

            <?php if (!empty($x['cuti'])): ?>
                <div class="space-y-4">
                    <?php foreach ($x['cuti'] as $cuti): ?>
                        <div class="p-4 transition duration-200 border border-gray-200 rounded-lg hover:bg-blue-50">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium text-gray-700">
                                    Cuti <?= Html::encode($cuti['jenis_cuti']) ?>
                                </span>
                                <span class="px-3 py-1 text-sm font-medium text-blue-700 bg-blue-100 rounded-full">
                                    <?= Html::encode($cuti['sisa']) ?> hari
                                </span>
                            </div>
                            <div class="grid grid-cols-3 gap-2 text-sm">
                                <div class="text-center">
                                    <p class="text-gray-500">Jatah</p>
                                    <p class="font-semibold"><?= Html::encode($cuti['jatah_hari_cuti']) ?> hari</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-gray-500">Terpakai</p>
                                    <p class="font-semibold text-orange-500"><?= Html::encode($cuti['total_terpakai']) ?> hari</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-gray-500">Sisa</p>
                                    <p class="font-semibold text-green-500"><?= Html::encode($cuti['sisa']) ?> hari</p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="py-4 text-center">
                    <i class="mb-2 text-3xl text-gray-300 fas fa-calendar-times"></i>
                    <p class="text-gray-500">Tidak ada data cuti</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Card Lembur -->
        <div class="p-6 bg-white border-l-4 shadow-lg rounded-xl border-amber-500">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Lembur</h2>
                    <p class="text-sm text-gray-500">Total jam lembur</p>
                </div>
                <div class="p-3 rounded-lg bg-amber-100">
                    <i class="text-xl fas fa-clock text-amber-600"></i>
                </div>
            </div>

            <div class="py-6 text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 mb-4 rounded-full bg-amber-100">
                    <span class="text-3xl font-bold text-amber-700"><?= Html::encode($x['lembur']) ?></span>
                </div>
                <p class="text-2xl font-bold text-gray-800">Jam</p>
                <p class="mt-2 text-sm text-gray-500">Total jam lembur periode ini</p>
            </div>

            <?php if (empty($x['wfh'])): ?>
                <div class="pt-6 mt-6 border-t border-gray-100">
                    <div class="flex items-center text-gray-500">
                        <i class="mr-2 fas fa-home"></i>
                        <span>Tidak ada data WFH</span>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Card Kasbon -->
        <div class="p-6 bg-white border-l-4 border-purple-500 shadow-lg rounded-xl">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Kasbon</h2>
                    <p class="text-sm text-gray-500">Informasi pinjaman</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="text-xl text-purple-600 fas fa-hand-holding-usd"></i>
                </div>
            </div>

            <?php if (!empty($x['kasbon'])): ?>
                <div class="space-y-6">
                    <!-- Jumlah Kasbon -->
                    <div class="p-5 transition duration-200 border-2 border-gray-300 rounded-lg bg-gray-50 hover:border-purple-400">
                        <div class="flex items-center justify-between mb-3">
                            <span class="font-medium text-gray-700">Total Kasbon</span>
                            <button onclick="toggleKasbonVisibility('jumlah_kasbon', this)"
                                class="p-2 text-purple-600 transition duration-200 rounded-full hover:text-purple-800 hover:bg-purple-50">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="relative">
                            <div id="jumlah_kasbon"
                                class="w-full font-mono text-2xl font-bold tracking-wider text-gray-800 bg-transparent border-none outline-none">
                                <span class="hidden" id="jumlah_kasbon_value">
                                    Rp <?= Html::encode(number_format($x['kasbon']['jumlah_kasbon'], 0, ',', '.')) ?>
                                </span>
                                <span class="password-mask">••••••••••</span>
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Total pinjaman yang diambil</p>
                    </div>

                    <!-- Sisa Kasbon -->
                    <div class="p-5 transition duration-200 border-2 border-gray-300 rounded-lg bg-gray-50 hover:border-purple-400">
                        <div class="flex items-center justify-between mb-3">
                            <span class="font-medium text-gray-700">Sisa Kasbon</span>
                            <button onclick="toggleKasbonVisibility('sisa_kasbon', this)"
                                class="p-2 text-purple-600 transition duration-200 rounded-full hover:text-purple-800 hover:bg-purple-50">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="relative">
                            <div id="sisa_kasbon"
                                class="w-full font-mono text-2xl font-bold tracking-wider text-gray-800 bg-transparent border-none outline-none">
                                <span class="hidden" id="sisa_kasbon_value">
                                    Rp <?= Html::encode(number_format($x['kasbon']['sisa_kasbon'], 0, ',', '.')) ?>
                                </span>
                                <span class="password-mask">••••••••••</span>
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Sisa pinjaman yang harus dibayar</p>
                    </div>

                    <!-- Info Tambahan -->
                    <div class="p-4 border border-purple-200 rounded-lg bg-purple-50">
                        <div class="flex items-start">
                            <i class="mt-1 mr-3 text-purple-500 fas fa-info-circle"></i>
                            <div>
                                <p class="text-sm font-medium text-purple-800">Informasi Pembayaran</p>
                                <p class="mt-1 text-xs text-purple-600">
                                    Bulan: <?= Html::encode($x['kasbon']['bulan']) ?> -
                                    Tahun: <?= Html::encode($x['kasbon']['tahun']) ?>
                                </p>
                                <?php if (!empty($x['kasbon']['deskripsi'])): ?>
                                    <p class="mt-1 text-xs text-purple-600">
                                        <?= Html::encode($x['kasbon']['deskripsi']) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="py-8 text-center">
                    <i class="mb-2 text-3xl text-gray-300 fas fa-check-circle"></i>
                    <p class="text-gray-500">Tidak ada kasbon</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer Info -->
    <div class="p-4 mt-8 border border-gray-200 bg-gray-50 rounded-xl">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="mr-2 text-blue-500 fas fa-exclamation-circle"></i>
                <span class="text-sm text-gray-600">Data diperbarui secara real-time</span>
            </div>
            <div class="text-sm text-gray-500">
                <i class="mr-1 far fa-clock"></i>
                Terakhir diakses: <?= date('d M Y H:i') ?>
            </div>
        </div>
    </div>
</div>

<!-- Include Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- JavaScript untuk toggle kasbon -->
<script>
    function toggleKasbonVisibility(id, button) {
        const element = document.getElementById(id);
        const valueElement = document.getElementById(id + '_value');
        const maskElement = element.querySelector('.password-mask');
        const icon = button.querySelector('i');

        if (valueElement.classList.contains('hidden')) {
            // Tampilkan nilai
            valueElement.classList.remove('hidden');
            maskElement.classList.add('hidden');
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');

            // Tambah efek visual
            element.closest('.border-gray-300').classList.add('border-purple-500', 'bg-purple-50');
        } else {
            // Sembunyikan nilai
            valueElement.classList.add('hidden');
            maskElement.classList.remove('hidden');
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');

            // Hapus efek visual
            element.closest('.border-gray-300').classList.remove('border-purple-500', 'bg-purple-50');
        }
    }
</script>

<style>
    .password-mask {
        font-family: 'Courier New', monospace;
        letter-spacing: 2px;
        font-size: 1.875rem;
        /* text-2xl */
        font-weight: bold;
        color: #374151;
        /* text-gray-800 */
    }

    /* Animasi untuk tombol */
    button i {
        transition: all 0.2s ease;
    }

    /* Efek hover untuk card kasbon */
    .hover\:border-purple-400:hover {
        border-color: #c084fc;
    }
</style>