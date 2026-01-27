<?php // Slip Gaji Ringkas A5 
?>

<div style="font-family:'Segoe UI',Tahoma,sans-serif;max-width:600px;margin:0 auto;border:1px solid #ccc;font-size:11px;line-height:1.4;">


    <!-- Header Slip Gaji dengan Logo di Kiri -->
    <div style="display:flex;  background:#2d5a7b;color:#fff;padding:6px 10px;align-items:center;justify-content:space-between;">

        <!-- Logo Kiri -->
        <div style="padding-inline: 10px; margin: 0px;">
            <img src="https://payroll.profaskes.id/images/logo.png" alt="Logo" style="width: 80px;  height: 80px; margin: 0px;">
        </div>


        <!-- Info Slip di Kanan -->
        <div>
            <div style="font-weight:bold;font-size:13px;">Payroll Profaskes</div>
            <div style="font-size:11px;opacity:0.9;">
                Periode <?= ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][$transaksiData["bulan"]] . ' ' . $transaksiData["tahun"]; ?><br>
                <?= $transaksiData["nama_bank"]; ?> - <?= $transaksiData["nomer_rekening"] ?> |
                Tgl: <?= date('d/m/Y', strtotime($transaksiData["created_at"])); ?>
            </div>
        </div>
    </div>

    <!-- Data Karyawan -->
    <div style="padding:5px 10px;border-bottom:1px solid #ddd;">
        <strong><?= $transaksiData["nama"]; ?></strong><br>
        <small><?= $transaksiData["nama_bagian"]; ?> - <?= $transaksiData["jabatan"]; ?> - <?= $transaksiData["status_pekerjaan"]; ?></small>
    </div>


    <!-- Pendapatan -->
    <div style="padding:4px 8px;border-bottom:1px solid #ddd;">
        <strong style="color:#2d5a7b;">Pendapatan</strong>
        <div style="margin-top:3px;font-size:11px;line-height:1.4;">
            <div style="display:flex;justify-content:space-between;border-bottom:1px solid #eee;padding:1px 0;">
                <span>Gaji Pokok</span>
                <span>Rp <?= number_format($transaksiData["nominal_gaji"], 0, ',', '.'); ?></span>
            </div>
            <div style="display:flex;justify-content:space-between;border-bottom:1px solid #eee;padding:1px 0;">
                <span>Tunjangan</span>
                <span>Rp <?= number_format($transaksiData["tunjangan_karyawan"], 0, ',', '.'); ?></span>
            </div>
            <div style="display:flex;justify-content:space-between;border-bottom:1px solid #eee;padding:1px 0;">
                <span>Lembur (<?= $transaksiData["jam_lembur"] ?? 0 ?> Jam)</span>
                <span>Rp <?= number_format($transaksiData["total_pendapatan_lembur"] ?? 0, 0, ',', '.'); ?></span>
            </div>
            <div style="display:flex;justify-content:space-between;border-bottom:1px solid #eee;padding:1px 0;">
                <span>Dinas Luar</span>
                <span>Rp <?= number_format($transaksiData["dinas_luar_belum_terbayar"], 0, ',', '.'); ?></span>
            </div>
            <div style="display:flex;justify-content:space-between;border-bottom:1px solid #eee;padding:1px 0;">
                <span>Pendapatan Lainnya</span>
                <span>Rp <?= number_format($transaksiData["pendapatan_lainnya"], 0, ',', '.'); ?></span>
            </div>
            <?php $totalPendapatan = $transaksiData["nominal_gaji"] + $transaksiData["tunjangan_karyawan"] + $transaksiData["total_pendapatan_lembur"] + $transaksiData["dinas_luar_belum_terbayar"] + $transaksiData["pendapatan_lainnya"]; ?>
            <div style="display:flex;justify-content:space-between;padding:2px 0;font-weight:bold;color:#2d5a7b;">
                <span>Total Pendapatan</span>
                <span>Rp <?= number_format($totalPendapatan, 0, ',', '.'); ?></span>
            </div>

        </div>
    </div>

    <!-- Potongan -->
    <div style="padding:4px 8px;border-bottom:1px solid #ddd;">
        <strong style="color:#a02020;">Potongan</strong>
        <div style="margin-top:3px;font-size:11px;line-height:1.4;">
            <div style="display:flex;justify-content:space-between;border-bottom:1px solid #eee;padding:1px 0;">
                <span>Standar</span>
                <span>Rp <?= number_format($transaksiData["potongan_karyawan"], 0, ',', '.'); ?></span>
            </div>
            <div style="display:flex;justify-content:space-between;border-bottom:1px solid #eee;padding:1px 0;">
                <span>Terlambat</span>
                <span>Rp <?= number_format($transaksiData["potongan_terlambat"], 0, ',', '.'); ?></span>
            </div>
            <div style="display:flex;justify-content:space-between;border-bottom:1px solid #eee;padding:1px 0;">
                <span>Absensi & WFH</span>
                <span>Rp <?= number_format($transaksiData["potongan_absensi"], 0, ',', '.'); ?></span>
            </div>
            <div style="display:flex;justify-content:space-between;border-bottom:1px solid #eee;padding:1px 0;">
                <span>Potongan Lainnya</span>
                <span>Rp <?= number_format($transaksiData["potongan_lainnya"], 0, ',', '.'); ?></span>
            </div>


            <?php if ($transaksiData["potongan_kasbon"]): ?>
                <div style="display:flex;justify-content:space-between;border-bottom:1px solid #eee;padding:1px 0;">
                    <span>Kasbon</span>
                    <span>Rp <?= number_format($transaksiData["potongan_kasbon"], 0, ',', '.'); ?></span>
                </div>
            <?php endif; ?>

            <?php

            // dd($transaksiData);
            $totalPotongan = $transaksiData["potongan_karyawan"]
                + $transaksiData["potongan_terlambat"]
                + $transaksiData["potongan_absensi"]
                + $transaksiData["potongan_lainnya"]
                + ($transaksiData["potongan_kasbon"] ?: 0);
            ?>
            <div style="display:flex;justify-content:space-between;padding:2px 0;font-weight:bold;color:#a02020;">
                <span>Total Potongan</span>
                <span>Rp <?= number_format($totalPotongan, 0, ',', '.'); ?></span>
            </div>
        </div>
    </div>

    <!-- Gaji Bersih -->
    <div style="padding:6px 10px;background:#f3f6f8;text-align:right;">
        <?php $gajiBersih = $totalPendapatan - $totalPotongan; ?>
        <div style="font-size:11px;color:#333;">Gaji Diterma:</div>
        <div style="font-size:16px;font-weight:bold;color:#2d5a7b;">Rp <?= number_format($transaksiData['gaji_diterima'], 0, ',', '.'); ?></div>
        <!-- <div style="font-size:10px;color:#888;">Dibayar: <?= date('d/m/Y'); ?></div> -->
    </div>
</div>