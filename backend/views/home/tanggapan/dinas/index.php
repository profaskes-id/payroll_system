<!--
  Heads up! 👋

  This component comes with some `rtl` classes. Please remove them if they are not needed in your project.
-->

<div class="w-full py-2 mx-5 overflow-x-auto">

    <div>
        <a href="<?= Yii::$app->urlManager->createUrl(['/tanggapan/dinas-create']) ?>" class="relative z-50 inline-block px-4 py-2 text-xs font-medium text-white bg-indigo-600 rounded-sm hover:bg-indigo-700">
            Tambah Pengajuan
        </a>
    </div>


    <table class="w-full min-w-full mt-10 text-sm bg-white divide-y-2 divide-gray-200">
        <thead class="text-left ">
            <tr>
                <th class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">Aksi</th>
                <th class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">Nama</th>
                <th class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">Alasan</th>
                <th class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">Tanggal</th>
                <th class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">Lokasi</th>
                <th class="px-4 py-2">Status</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-200">

            <?php
            foreach ($pengajuanDinasList as $item) {
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
                <tr>
                    <td class="px-4 py-2 text-gray-700 whitespace-nowrap">

                        <a href="<?= Yii::$app->urlManager->createUrl(['home/tanggapan/dinas/detail', 'id_pengajuan_dinas' => $item['id_pengajuan_dinas']]) ?>" class="relative z-50 inline-block px-4 py-2 text-xs font-medium text-white bg-indigo-600 rounded-sm hover:bg-indigo-700">
                            <?php echo $item['status'] == 0 ? 'Tanggapi' : 'Lihat' ?>

                        </a>
                    </td>
                    <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"><?= $item->karyawan->nama ?></td>
                    <td class="px-4 py-2 text-gray-700 whitespace-nowrap"><?= $item->alasan ?></td>
                    <td class="px-4 py-2 text-gray-700 whitespace-nowrap"><?= json_decode($item['tanggal_array'])[0] ?></td>
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