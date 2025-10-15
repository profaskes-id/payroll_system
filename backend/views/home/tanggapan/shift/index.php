<?php

use backend\models\helpers\KaryawanHelper;
use backend\models\MasterKode;
use backend\models\Tanggal;
use yii\widgets\ActiveForm;

$tanggalFormater = new Tanggal();
?>




<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />

<!-- Include jQuery (required for DataTables) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include JS for DataTables -->
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>



<style>
    .dt-input:first-child {
        width: 50px;
    }
</style>


<h1 class="p-5 text-2xl font-bold">Pengajuan Shift</h1>



<div class="relative z-40 px-5 overflow-x-auto">
    <table class="w-full text-sm text-left text-gray-500 rtl:text-right dark:text-gray-400" id="table_id" class="display">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-1">
                    Action
                </th>
                <th scope="col" class="px-6 py-1">
                    Nama
                </th>
                <th scope="col" class="px-6 py-1">
                    Alasan
                </th>
                <th scope="col" class="px-6 py-1">
                    Tanggal
                </th>
                <th scope="col" class="px-6 py-1">
                    Status
                </th>

            </tr>
        </thead>
        <tbody>

            <?php
            foreach (array_reverse($pengajuanShiftList) as $item) {

                $statusColor = '';
                $statusText = '';
                switch ($item['status']) {
                    case 0:
                        $statusColor = ' text-yellow-500';
                        $statusText = 'Pending';
                        break;
                    case 1:
                        $statusColor = ' text-green-500';
                        $statusText = 'Disetujui';
                        break;
                    case 2:
                        $statusColor = ' text-red-500';
                        $statusText = 'Ditolak';
                        break;
                }
            ?>


                <tr class="bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                    <td class="px-6">
                        <p class="flex items-center justify-start">
                            <a href="<?= Yii::$app->urlManager->createUrl(['/tanggapan/shift-view', 'id_pengajuan_shift' => $item['id_pengajuan_shift']]) ?>" class="relative flex items-center  justify-center p-1.5 bg-[#488aec] rounded-md">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24">
                                    <path fill="white" d="M12 9a3 3 0 0 1 3 3a3 3 0 0 1-3 3a3 3 0 0 1-3-3a3 3 0 0 1 3-3m0-4.5c5 0 9.27 3.11 11 7.5c-1.73 4.39-6 7.5-11 7.5S2.73 16.39 1 12c1.73-4.39 6-7.5 11-7.5M3.18 12a9.821 9.821 0 0 0 17.64 0a9.821 9.821 0 0 0-17.64 0" />
                                </svg>

                            </a>
                        </p>
                    </td>
                    <td class="">
                        <?= $item->karyawan->nama ?>
                    </td>
                    <td class="px-6 py-2 text-xs">
                        <?= $item->shiftKerja->nama_shift ?>
                    </td>
                    <td class="px-6 py-2">
                        <?php

                        echo "<p class='text-xs'>" . $tanggalFormater->getIndonesiaFormatTanggal($item->diajukan_pada) . "</p>";
                        ?>
                    </td>
                    <td class=" text-xs<?= $statusColor ?>">
                        <?= $statusText ?>

                    </td>

                </tr>


            <?php } ?>


        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        $('#table_id').DataTable(); // Initialize DataTables
    });
</script>