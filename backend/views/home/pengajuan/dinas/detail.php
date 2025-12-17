<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<section class="w-full mx-auto md:px-x lg:px-8 min-h-[90dvh]  relative z-50">
    <?= $this->render('@backend/views/components/_header', ['link' => '/panel/pengajuan/dinas', 'title' => 'Pengajuan Dinas Luar']); ?>


    <div class="bg-gray-100/50 w-full h-[80dvh] rounded-md   relative ">

        <div class="w-full p-3 bg-white rounded-md">
            <p class="text-sm fw-bold">Status : <?= $model['status'] == '0' ? '<span class="text-yellow-500">Pending</span>' : ($model['status'] == '1' ? '<span class="text-green-500">Disetujui</span>' : '<span class="text-rose-500">Ditolak</span>')  ?></p>
        </div>
        <div class="w-full p-2 mt-2 text-black bg-white rounded-md">
            <p class="text-sm text-gray-500 capitalize">Keterangan Perjalanan</p>
            <p><?= $model['keterangan_perjalanan'] ?></p>
            <hr class="my-2">
            <p class="text-sm text-gray-500 capitalize">Catatan Admin : </p>
            <p><?= $model['catatan_admin']  ?? '-' ?></p>
            <hr class="my-2">
            <p class="text-sm text-gray-500 capitalize">diajukan untuk tanggal</p>
            <div class="flex space-x-3 text-gray-500">
                <?php
                $detailDinas = $model->detailDinas;
                if (!empty($detailDinas)):
                ?>
                    <div class="flex flex-wrap gap-1">
                        <?php foreach ($detailDinas as $detail): ?>
                            <?php
                            $tanggalFormatted = Yii::$app->formatter->asDate($detail->tanggal, 'php:d M Y');
                            $status = $detail->status ?? null;

                            // Tentukan class berdasarkan status
                            if ($status == 1) {
                                // Status 1 = Disetujui = Hijau
                                $bgColor = 'bg-green-100';
                                $textColor = 'text-green-800';
                                $statusText = ' (Disetujui)';
                            } elseif ($status == 2) {
                                // Status 2 = Ditolak = Merah
                                $bgColor = 'bg-red-100';
                                $textColor = 'text-red-800';
                                $statusText = ' (Ditolak)';
                            } else {
                                // Status lainnya atau belum ada status = Biru (default)
                                $bgColor = 'bg-blue-100';
                                $textColor = 'text-blue-800';
                                $statusText = '';
                            }
                            ?>
                            <span class="inline-block px-2 py-1 text-xs <?= $textColor ?> <?= $bgColor ?> rounded-full font-medium">
                                <?= $tanggalFormatted . $statusText ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>


        <!-- dokumentasi
         -->
    </div>

</section>