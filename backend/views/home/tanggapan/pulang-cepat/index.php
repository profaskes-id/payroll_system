<?php

use backend\models\Tanggal;
use yii\helpers\Html;
use yii\helpers\Url;

$tanggalFormater = new Tanggal();
?>

<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>

<style>
    .status-pending {
        color: #FFC107;
        font-weight: bold;
    }

    .status-approved {
        color: #28A745;
        font-weight: bold;
    }

    .status-rejected {
        color: #DC3545;
        font-weight: bold;
    }
</style>

<h1 class="p-5 text-2xl font-bold">Pengajuan Pulang Cepat</h1>

<div class="relative z-40 px-5 overflow-x-auto">
    <table class="w-full text-sm text-left text-gray-600" id="table_id">
        <thead class="text-xs text-gray-700 uppercase bg-gray-100">
            <tr>
                <th class="px-6 py-3">Aksi</th>
                <th class="px-6 py-3">Nama Karyawan</th>
                <th class="px-6 py-3">Tanggal</th>
                <th class="px-6 py-3">Alasan</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Disetujui Pada</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (array_reverse($listPengajuan) as $item): ?>
                <?php
                switch ($item->status) {
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
                    default:
                        $statusClass = '';
                        $statusText = 'Tidak Diketahui';
                }
                ?>
                <tr class="bg-white border-b">
                    <td class="px-6 py-4">
                        <a href="<?= Url::to(['/tanggapan/pulang-cepat-view', 'id' => $item->id_izin_pulang_cepat]) ?>"
                            class="flex items-center justify-center p-1.5 bg-blue-500 rounded-md text-white hover:bg-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M12 9a3 3 0 0 1 3 3a3 3 0 0 1-3 3a3 3 0 0 1-3-3a3 3 0 0 1 3-3m0-4.5c5 0 9.27 3.11 11 7.5c-1.73 4.39-6 7.5-11 7.5S2.73 16.39 1 12c1.73-4.39 6-7.5 11-7.5M3.18 12a9.821 9.821 0 0 0 17.64 0a9.821 9.821 0 0 0-17.64 0" />
                            </svg>
                        </a>
                    </td>
                    <td class="px-6 py-4"><?= $item->karyawan->nama ?? '-' ?></td>
                    <td class="px-6 py-4"><?= $item->tanggal ?? '-' ?></td>
                    <td class="px-6 py-4"><?= nl2br(Html::encode($item->alasan)) ?></td>
                    <td class="px-6 py-4 <?= $statusClass ?>"><?= $statusText ?></td>
                    <td class="px-6 py-4"><?= $item->disetujui_pada ? Yii::$app->formatter->asDatetime($item->disetujui_pada) : '-' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        $('#table_id').DataTable({
            responsive: true,
            columnDefs: [{
                    responsivePriority: 1,
                    targets: 0
                }, // Aksi
                {
                    responsivePriority: 2,
                    targets: 1
                }, // Nama
                {
                    responsivePriority: 3,
                    targets: 3
                }, // Alasan
            ]
        });
    });
</script>