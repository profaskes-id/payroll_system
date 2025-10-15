<?php

/** @var yii\web\View $this */
/** @var array $model */ // Assuming $model is passed to the view

use backend\models\Tanggal;
use yii\helpers\Html;
use yii\helpers\Url;

$tanggalFormater = new Tanggal();
?>

<?php if (empty($model) || empty($model['id_pengajuan_cuti'])) : ?>
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
<?php else: ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <div class="container relative z-50 p-6 mx-auto">
        <div class="flex items-center justify-between">

            <h1 class="mb-4 text-2xl font-bold">Detail Pengajuan cuti</h1>
            <p class="">
                <?php echo Html::a('Back', ['/tanggapan/cuti'], ['class' => 'costume-btn']) ?>
            </p>
        </div>


        <div class="flex items-center justify-between my-5">
            <div class="flex justify-start space-x-4">

                <?php echo Html::a('Tanggapi', ['cuti-update', 'id_pengajuan_cuti' => $model['id_pengajuan_cuti']], ['class' => 'tw-add bg-blue-500 px-6 relative']) ?>

                <?= Html::button('Delete', [
                    'class' => 'btn-delete tw-add bg-red-500 px-5 text-white rounded',
                    'data-url' => Url::to(['cuti-delete', 'id_pengajuan_cuti' => $model['id_pengajuan_cuti']])
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
                        <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?php echo Html::encode($model['karyawan']['nama']) ?></td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Jenis Cuti</td>
                        <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= $model['jenisCuti']['jenis_cuti'] ?></td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Alasan Cuti</td>
                        <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= $model['alasan_cuti'] ?></td>
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
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Ditanggapi Pada</td>
                        <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= Html::encode($model['ditanggapi_pada'] ?? 'Belum ditanggapi') ?></td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Ditanggapi Oleh</td>
                        <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                            <?= Html::encode($model['   ']['profile']['full_name'] ?? $model['disetujuiOleh']['username'] ?? 'Belum disetujui') ?>
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


    <h1>detail cuti</h1>
    <div class="relative z-40">

        <table class="relative min-w-full text-sm border border-gray-300 divide-y divide-gray-200 rounded-md shadow-sm z">
            <thead class="bg-gray-100">
                <tr class="text-center text-gray-700">
                    <th class="w-12 px-3 py-2 border border-gray-300">#</th>
                    <th class="px-3 py-2 border border-gray-300">Tanggal</th>
                    <th class="px-3 py-2 border border-gray-300">Keterangan</th>
                    <th class="px-3 py-2 border border-gray-300">Status</th>
                    <th class="w-16 px-3 py-2 border border-gray-300">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                <?php foreach ($model->detailCuti as $i => $detail): ?>
                    <tr class="text-center hover:bg-gray-50">
                        <!-- No -->
                        <td class="px-3 py-2 border border-gray-300"><?= $i + 1 ?></td>

                        <!-- Tanggal -->
                        <td class="px-3 py-2 border border-gray-300">
                            <?= Yii::$app->formatter->asDate($detail->tanggal, 'php:d M Y') ?>
                        </td>

                        <!-- Keterangan -->
                        <td class="px-3 py-2 text-left border border-gray-300">
                            <?= $detail->keterangan ?? '-' ?>
                        </td>

                        <!-- Status -->
                        <td class="px-3 py-2 border border-gray-300">
                            <?php
                            $statusLabel = match ((int)$detail->status) {
                                0 => '<span class="font-medium text-yellow-600">Pending</span>',
                                1 => '<span class="font-medium text-green-600">Disetujui</span>',
                                2 => '<span class="font-medium text-red-600">Ditolak</span>',
                                default => '<span class="text-gray-500">Tidak Diketahui</span>',
                            };
                            echo $statusLabel;
                            ?>
                        </td>

                        <!-- Aksi -->
                        <td class="px-3 py-2 border border-gray-300">
                            <?= Html::button(
                                '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3m5 0H6" />
        </svg>',
                                [
                                    'class' => 'btn-delete-child inline-flex items-center justify-center bg-red-600 hover:bg-red-700 text-white rounded p-1',
                                    'data-url' => Url::to([
                                        'delete-cuti-detail',
                                        'id' => $detail->id_detail_cuti,
                                        'id_pengajuan_cuti' => $detail->id_pengajuan_cuti
                                    ]),
                                    'title' => 'Hapus'
                                ]
                            ) ?>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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

    <script>
        $(document).ready(function() {
            $('.btn-delete-child').on('click', function() {
                const url = $(this).data('url');

                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Detail data ini akan dihapus permanen!",
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
                        // Redirect ke URL delete (GET method assumed)
                        window.location.href = url;
                    }
                });
            });
        });
    </script>


<?php endif ?>