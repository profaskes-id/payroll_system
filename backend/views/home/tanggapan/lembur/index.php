<?php

use backend\models\Tanggal;

$tanggalFormater = new Tanggal();
?>

<div class="w-full py-2 mx-5 overflow-x-hidden ">

    <div class="flex justify-end px-10">
        <a href="<?= Yii::$app->urlManager->createUrl(['/tanggapan/wfh-create']) ?>" class="relative px-6 tw-add">
            Tambah Pengajuan WFH
        </a>
    </div>

    <table class="w-[100%] mt-5 text-sm divide-y-2 divide-gray-200">
        <thead class="text-left border-t-2">
            <tr class="divide-x ">
                <th class="px-4 py-2 font-medium text-center text-gray-900 whitespace-nowrap">Aksi</th>
                <th class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">Nama</th>
                <th class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">Alasan</th>
                <th class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">Tanggal</th>
                <th class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">Lokasi</th>
                <th class="px-4 py-2">Status</th>
            </tr>
        </thead>

        <tbody class="divide-y gray-200">

            <?php
            foreach (array_reverse($pengajuanWfhList) as $item) {
                $statusColor = '';
                $statusText = '';
                switch ($item['status']) {
                    case 0:
                        $statusColor = 'bg-yellow-500';
                        $statusText = 'Pending';
                        break;
                    case 1:
                        $statusColor = 'bg-green-500';
                        $statusText = 'Disetujui';
                        break;
                    case 2:
                        $statusColor = 'bg-red-500';
                        $statusText = 'Ditolak';
                        break;
                }
            ?>
                <tr class="divide-x">
                    <td class="w-[30px]  flex justify-center items-center text-gray-700 whitespace-nowrap py-1 px-2">

                        <a href="<?= Yii::$app->urlManager->createUrl(['/tanggapan/wfh-view', 'id_pengajuan_wfh' => $item['id_pengajuan_wfh']]) ?>" class="relative flex items-center  justify-center tw-add w-[50px]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M12 9a3 3 0 0 1 3 3a3 3 0 0 1-3 3a3 3 0 0 1-3-3a3 3 0 0 1 3-3m0-4.5c5 0 9.27 3.11 11 7.5c-1.73 4.39-6 7.5-11 7.5S2.73 16.39 1 12c1.73-4.39 6-7.5 11-7.5M3.18 12a9.821 9.821 0 0 0 17.64 0a9.821 9.821 0 0 0-17.64 0" />
                            </svg>

                        </a>
                    </td>
                    <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"><?= $item->karyawan->nama ?></td>
                    <td class="px-4 py-2 text-xs text-gray-700 whitespace-nowrap"><?= $item->alasan ?></td>
                    <td class="px-4 py-2 text-gray-700 whitespace-nowrap">

                        <?php
                        $json_tanggal_array = $item['tanggal_array'];
                        $tanggal_array = json_decode($json_tanggal_array, true);
                        foreach ($tanggal_array as $tanggal) {
                            echo "<p class='text-xs'>" . $tanggalFormater->getIndonesiaFormatTanggal($tanggal) . "</p>";
                        }
                        ?>
                    </td>
                    <td class="px-4 py-2 text-gray-700 whitespace-nowrap"><?= $item->lokasi ?></td>
                    <td class="px-4 py-2 whitespace-nowrap">
                        <a
                            href="#"
                            class="inline-block px-4 py-2 text-xs font-medium text-white  <?= $statusColor ?> rounded-sm hover:bg-indigo-700">
                            <?= $statusText ?>

                        </a>
                    </td>
                </tr>
            <?php } ?>


        </tbody>
    </table>