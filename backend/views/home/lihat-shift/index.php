<?php

use yii\helpers\Html;
use backend\models\ShiftKerja;

function hariIndo($tanggal)
{
    $days = [
        'Sunday'    => 'Minggu',
        'Monday'    => 'Senin',
        'Tuesday'   => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday'  => 'Kamis',
        'Friday'    => 'Jumat',
        'Saturday'  => 'Sabtu',
    ];
    $date = new DateTime($tanggal);
    return $days[$date->format('l')];
}

function formatTanggalIndo($tanggal)
{
    $bulan = [
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember'
    ];

    $date = new DateTime($tanggal);
    $d = $date->format('d');
    $m = $date->format('m');
    $y = $date->format('Y');

    return "$d " . $bulan[$m] . " $y";
}

// Daftar warna latar berbeda untuk tiap shift
$shiftColors = [
    1 => 'bg-purple-300',
    2 => 'bg-green-300',
    3 => 'bg-yellow-300',
    4 => 'bg-red-300',
    5 => 'bg-blue-300',
    // tambahkan sesuai jumlah shift yang kamu punya
];
?>

<div class="container px-4 py-6 mx-auto">
    <?= $this->render('@backend/views/components/_header', ['link' => '/panel/home', 'title' => 'Jadwal Shift Kerja ']); ?>
    <a href="/panel/home/pengajuan-shift?id_karyawan=<?= Yii::$app->user->identity->id_karyawan ?>">
        <p class="mb-4 text-blue-600 underline">Buat Perubahan Shift Kerja Anda ? </p>
    </a>


    <div class="overflow-x-auto">
        <table class="min-w-full text-sm bg-white border border-gray-200 rounded-lg shadow-sm">
            <thead>
                <tr class="text-left text-gray-700 bg-gray-100">
                    <th class="px-4 py-2 border-b">Tanggal</th>
                    <th class="px-4 py-2 border-b">Hari</th>
                    <th class="px-4 py-2 border-b">Shift Kerja</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($model as $item): ?>
                    <?php
                    $shift = ShiftKerja::findOne($item['id_shift_kerja']);
                    $namaShift = $shift ? $shift->nama_shift : '-';
                    $warnaBaris = $shiftColors[$item['id_shift_kerja']] ?? 'bg-white';
                    ?>
                    <tr class="<?= $warnaBaris ?> hover:bg-gray-100 transition">
                        <td class="px-4 py-2 border-b"><?= formatTanggalIndo($item['tanggal']) ?></td>
                        <td class="px-4 py-2 border-b"><?= hariIndo($item['tanggal']) ?></td>
                        <td class="px-4 py-2 font-medium text-gray-800 border-b"><?= Html::encode($namaShift) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>