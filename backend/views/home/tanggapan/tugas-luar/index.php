<?php
use backend\models\helpers\KaryawanHelper;
use backend\models\MasterKode;
use backend\models\Tanggal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$tanggalFormater = new Tanggal();
?>

<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>

<style>
    .dt-input:first-child {
        width: 50px;
    }
    .status-pending { color: #FFC107; }
    .status-approved { color: #28A745; }
    .status-rejected { color: #DC3545; }
</style>

<h1 class="p-5 text-2xl font-bold">Pengajuan Tugas Luar</h1>

<div class="relative z-50 px-5 overflow-x-auto">
    <table class="w-full text-sm text-left text-gray-500 rtl:text-right dark:text-gray-400" id="table_id">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">Action</th>
                <th scope="col" class="px-6 py-3">Nama Karyawan</th>
                <th scope="col" class="px-6 py-3">Tanggal Tugas</th>
                <th scope="col" class="px-6 py-3">Catatan Approver</th>
                <th scope="col" class="px-6 py-3">Status</th>

            </tr>
        </thead>
        <tbody>
            <?php foreach (array_reverse($pengajuanTugasLuarList) as $item): 
                $statusClass = '';
                $statusText = '';
                switch ($item['status_pengajuan']) {
                    case 0:
                        $statusClass = 'status-pending';
                        $statusText = 'Pending';
                        break;
                    case 1:
                        $statusClass = 'status-approved';
                        $statusText = 'Disetujui';
                        break;
                    case 2:
                        $statusClass = 'status-rejected';
                        $statusText = 'Ditolak';
                        break;
                }
            ?>
                <tr class="bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                    <td class="px-6 py-4">
                        <a href="<?= Yii::$app->urlManager->createUrl(['/tanggapan/tugas-luar-view', 'id' => $item['id_tugas_luar']]) ?>" 
                           class="flex items-center justify-center p-1.5 bg-blue-500 rounded-md text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M12 9a3 3 0 0 1 3 3a3 3 0 0 1-3 3a3 3 0 0 1-3-3a3 3 0 0 1 3-3m0-4.5c5 0 9.27 3.11 11 7.5c-1.73 4.39-6 7.5-11 7.5S2.73 16.39 1 12c1.73-4.39 6-7.5 11-7.5M3.18 12a9.821 9.821 0 0 0 17.64 0a9.821 9.821 0 0 0-17.64 0"/>
                            </svg>
                        </a>
                    </td>
                    <td class="px-6 py-4"><?= $item->karyawan->nama ?? '-' ?></td>
                    <td class="px-6 py-4"><?= $item->tanggal ?></td>
                    <td class="px-6 py-4"><?= $item->catatan_approver ?? '-' ?></td>
                    <td class="px-6 py-4 <?= $statusClass ?>"><?= $statusText ?></td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        $('#table_id').DataTable({
            responsive: true,
            columnDefs: [
                { responsivePriority: 1, targets: 0 }, // Action column
                { responsivePriority: 2, targets: 1 }, // Nama Karyawan
                { responsivePriority: 3, targets: 3 }, // Catatan Approver
                { responsivePriority: 4, targets: 4 }  // Status
            ]
        });
    });
</script>