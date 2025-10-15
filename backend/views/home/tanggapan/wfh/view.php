<?php

/** @var yii\web\View $this */
/** @var array $model */ // Assuming $model is passed to the view

use backend\models\Tanggal;
use yii\helpers\Html;
use yii\helpers\Url;

$tanggalFormater = new Tanggal();
?>


<?php if (empty($model) || empty($model['id_pengajuan_wfh'])) : ?>
    <div class="relative z-40 max-w-md p-6 mx-auto my-8 bg-white shadow-lg rounded-xl">
        <div class="flex items-center justify-center mb-4">
            <div class="p-3 bg-blue-100 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
        </div>

        <h2 class="mb-2 text-2xl font-bold text-center text-gray-800">Data Tidak Ditemukan</h2>

        <p class="mb-6 text-center text-gray-600">
            Sepertinya data ini telah dihapus dari sistem.
        </p>

        <div class="p-4 mb-6 border-l-4 border-blue-500 rounded bg-blue-50">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        <span class="font-medium">Kode Error:</span>
                        <code class="px-2 py-1 font-mono text-blue-800 bg-blue-100 rounded">DATA_DELETED_404</code>
                    </p>
                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="javascript:history.back()" class="inline-flex items-center px-4 py-2 font-medium text-white transition duration-200 bg-blue-500 rounded-md hover:bg-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </div>

<?php else : ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="container relative z-40 p-0 mx-auto md:p-6">


        <div class="flex items-center justify-between">

            <h1 class="mb-4 text-2xl font-bold">Detail Pengajuan WFH</h1>
            <p class="">
                <?= Html::a('Back', ['/tanggapan/wfh'], ['class' => 'costume-btn']) ?>
            </p>
        </div>


        <div class="flex items-center justify-between my-5">
            <div class="flex justify-start space-x-4">

                <?= Html::a('Tanggapi', ['wfh-update', 'id' => $model['id_pengajuan_wfh']], ['class' => 'tw-add bg-blue-500 px-6 relative']) ?>
                <?= Html::button('Delete', [
                    'class' => 'btn-delete tw-add bg-red-500 px-5 text-white rounded',
                    'data-url' => Url::to(['wfh-delete', 'id_pengajuan_wfh' => $model['id_pengajuan_wfh']])
                ]) ?>


            </div>
        </div>




        <div class="overflow-hidden bg-white rounded-lg shadow-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Field</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Value</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">

                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Karyawan</td>
                        <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= Html::encode($model['karyawan']['nama']) ?></td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Alasan</td>
                        <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= Html::encode($model['alasan']) ?></td>
                    </tr>
                    <!-- <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Alamat</td>
                        <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><= Html::encode($model['alamat']) ?></td>
                    </tr> -->

                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Lokasi</td>
                        <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                            <?php if (!empty($model['latitude']) && !empty($model['longitude'])): ?>
                                <a href="https://www.google.com/maps?q=<?= Html::encode($model['latitude']) ?>,<?= Html::encode($model['longitude']) ?>"
                                    target="_blank"
                                    class="inline-flex items-center px-3 py-1 text-sm font-medium text-blue-600 rounded-md bg-blue-50 hover:bg-blue-100 hover:text-blue-700"
                                    title="Buka di Google Maps">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Lihat Peta
                                </a>
                                <div class="mt-1 text-xs text-gray-500">
                                    Lat: <?= Html::encode($model['latitude']) ?>, Long: <?= Html::encode($model['longitude']) ?>
                                </div>
                            <?php else: ?>
                                <span class="text-gray-400">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Tanggal</td>
                        <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                            <?php
                            $json_tanggal_array = $model['tanggal_array'];
                            $tanggal_array = json_decode($json_tanggal_array, true);
                            foreach ($tanggal_array as $tanggal) {
                                echo "<p class='text-base'>" . $tanggalFormater->getIndonesiaFormatTanggal($tanggal) . "</p>";
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Status</td>
                        <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">

                            <?php
                            if ($model['status'] == 0) {
                                echo '<span class="text-yellow-500">Menuggu Tanggapan</span>';
                            } elseif ($model['status'] == 1) {
                                echo '<span class="text-green-500">Pengajuan Telah Disetujui</span>';
                            } elseif ($model['status'] == 2) {
                                echo '<span class="text-danger">Pengajuan  Ditolak</span>';
                            } else {
                                echo "<span class='text-rose-500'>master kode tidak aktif</span>";
                            } ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Disetujui Pada</td>

                        <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= Html::encode($model['disetujui_pada'] ?? 'Belum disetujui') ?></td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Disetujui Oleh</td>
                        <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                            <?= Html::encode($model['disetujuiOleh']['profile']['full_name'] ?? $model['disetujuiOleh']['username'] ?? 'Belum disetujui') ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Catatan Admin</td>
                        <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= Html::encode($model['catatan_admin'] ?? 'Tidak ada catatan') ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js" integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            $('.btn-delete').on('click', function() {
                const url = $(this).data('url');

                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        confirmButton: 'bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded focus:outline-none',
                        cancelButton: 'bg-gray-300 hover:bg-gray-400 text-black px-4 py-2 rounded focus:outline-none ml-2'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect ke URL delete (GET request)
                        window.location.href = url;
                    }
                });
            });
        });
    </script>
<?php endif; ?>