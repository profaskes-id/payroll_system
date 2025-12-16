<?php

/** @var yii\web\View $this */
/** @var array $model */

use backend\models\Tanggal;
use yii\helpers\Html;
use yii\helpers\Url;

$tanggalFormater = new Tanggal();
?>
<?php if (empty($model) || empty($model['id_tugas_luar'])) : ?>
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

    <div class="container relative z-40 p-0 mx-auto md:p-6">
        <div class="flex items-center justify-between">
            <h1 class="mb-4 text-2xl font-bold">Detail Pengajuan Tugas Luar</h1>
            <p class="">
                <?= Html::a('Back', ['/tanggapan/tugas-luar'], ['class' => 'costume-btn']) ?>
            </p>
        </div>

        <div class="flex items-center justify-between my-5">
            <div class="flex justify-start space-x-4">
                <?= Html::a('Tanggapi', ['tugas-luar-update', 'id_tugas_luar' => $model['id_tugas_luar']], ['class' => 'tw-add bg-blue-500 px-6 relative']) ?>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <?= Html::button('Delete', [
                    'class' => 'btn-delete tw-add bg-red-500 px-5 text-white rounded',
                    'data-url' => Url::to(['tugas-luar-delete', 'id_tugas_luar' => $model['id_tugas_luar']])
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
                        <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= Html::encode($model['karyawan']['nama'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 capitalize whitespace-nowrap">Tanggal Tugas</td>
                        <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= $model['tanggal'] ?></td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Status Pengajuan</td>
                        <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                            <?php
                            if ($model['status_pengajuan'] == 0) {
                                echo '<span class="text-yellow-500">Pending</span>';
                            } elseif ($model['status_pengajuan'] == 1) {
                                echo '<span class="text-green-500">Disetujui</span>';
                            } elseif ($model['status_pengajuan'] == 2) {
                                echo '<span class="text-danger">Ditolak</span>';
                            } else {
                                echo '<span class="text-rose-500">Status tidak valid</span>';
                            } ?>
                        </td>
                    </tr>


                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Catatan Approver</td>
                        <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= Html::encode($model['catatan_approver'] ?? 'Tidak ada catatan') ?></td>
                    </tr>

                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Dibuat Pada</td>
                        <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                            <?= Html::encode(
                                $model['updated']['profile']['full_name'] ??
                                    $model['updated']['username'] ??
                                    'Belum disetujui'
                            ) ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Diupdate Pada</td>
                        <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= $model['updated_at'] ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <h4 class="mt-4 text-lg font-medium text-gray-900">Detail Tugas Luar</h4>

        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">#</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Keterangan</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Jam Diajukan</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Jam Check In</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status Check</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Bukti Foto</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Lokasi</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">

                    <?php
                    $data =  $model->detailTugasLuars ?? [];
                    ?>
                    <?php foreach ($data as $index => $detail): ?>
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap"><?= $index + 1 ?></td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap"><?= Html::encode($detail->keterangan) ?></td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap"><?= date('H:i', strtotime($detail->jam_diajukan)) ?></td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                <?= $detail->jam_check_in ? date('H:i', strtotime($detail->jam_check_in)) : '-' ?>
                            </td>
                            <td class="px-6 py-4 text-sm whitespace-nowrap">
                                <?php if ($detail->status_check === 1): ?>
                                    <span class="text-green-600">Checked In</span>
                                <?php else: ?>
                                    <span class="text-yellow-600">Belum Check In</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-center text-gray-500 whitespace-nowrap">
                                <?php if ($detail->bukti_foto): ?>
                                    <?php $imageUrl = Yii::getAlias('@web/uploads/bukti_tugas_luar/') . $detail->bukti_foto; ?>
                                    <a href="<?= $imageUrl ?>" target="_blank" title="Klik untuk melihat ukuran penuh">
                                        <img src="<?= $imageUrl ?>"
                                            class="inline-block object-cover w-20 border border-gray-200 rounded-md h-15"
                                            alt="Bukti Tugas Luar">
                                    </a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-center text-gray-500 whitespace-nowrap">
                                <?php if ($detail->latitude && $detail->longitude): ?>
                                    <?php $mapUrl = "https://www.google.com/maps?q={$detail->latitude},{$detail->longitude}"; ?>
                                    <a href="<?= $mapUrl ?>"
                                        target="_blank"
                                        title="Buka di Google Maps"
                                        class="inline-flex items-center px-3 py-1 text-sm font-medium text-blue-700 bg-white border border-blue-300 rounded-md shadow-sm hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="w-4 h-4 mr-1 -ml-1 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                        </svg>
                                        Lihat Peta
                                    </a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm whitespace-nowrap">
                                <?php if ($detail->status_pengajuan_detail !== null): ?>
                                    <?php if ($detail->status_pengajuan_detail == 0): ?>
                                        <span class="text-yellow-600">Pending</span>
                                    <?php elseif ($detail->status_pengajuan_detail == 1): ?>
                                        <span class="text-green-600">Disetujui</span>
                                    <?php elseif ($detail->status_pengajuan_detail == 2): ?>
                                        <span class="text-red-600">Ditolak</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-red-600">master kode tidak aktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-center text-gray-500 whitespace-nowrap">
                                <a href="javascript:void(0);"
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-red-600 border border-transparent rounded-md btn-delete-detail hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                    data-url="<?= Yii::$app->urlManager->createUrl(['/tanggapan/tugas-luar-delete-detail', 'id_tugas_luar' => $model->id_tugas_luar, 'id_detail' => $detail->id_detail]) ?>"
                                    title="Hapus Detail">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </a>


                            </td>
                        </tr>
                    <?php endforeach; ?>
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



            $('.btn-delete-detail').on('click', function() {
                const url = $(this).data('url');

                Swal.fire({
                    title: 'Yakin ingin menghapus detail ini?',
                    text: "Detail tugas luar akan dihapus permanen.",
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
                        // Redirect ke URL delete detail (GET request)
                        window.location.href = url;
                    }
                });
            });

        });
    </script>
<?php endif; ?>