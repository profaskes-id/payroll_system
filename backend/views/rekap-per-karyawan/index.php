<?php
$this->title = 'Rekap Absensi ';

use yii\helpers\Html;
?>
<div class="row">
    <div class="col-12 col-md-9">
        <button style="width: 100%;" class="add-button" type="submit" data-toggle="collapse" data-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample">
            <i class="fas fa-search"></i>
            <span>
                Search
            </span>
        </button>
    </div>

    <?php
    // Pastikan nilai parameter ada atau kosong jika tidak ada
    $params = [];

    $params['id_karyawan'] = isset($karyawan) ? $karyawan['id_karyawan'] : '';
    $params['tanggal_awal'] = isset($tanggal_awal) ? $tanggal_awal : '';
    $params['tanggal_akhir'] = isset($tanggal_akhir) ? $tanggal_akhir : '';
    ?>


    <!-- Trigger Modal -->
    <div class="mt-2 mt-md-0 col-md-3">
        <p class="d-block">
            <a href="#" class=" tambah-button" data-toggle="modal" data-target="#exportModal">
                Export to Excel <i class="fa fa-table"></i>
            </a>
        </p>
    </div>

</div>
<div style="margin-top: 10px;">
    <div class="collapse width" id="collapseWidthExample">
        <div style="width: 100%;">
            <?= $this->render('_search', ['tanggal_awal' => $tanggal_awal, 'tanggal_akhir' => $tanggal_akhir,]) ?>
        </div>
    </div>
</div>

<?= $this->render('@backend/views/rekap-per-karyawan/_modal', ['model' => $model]) ?>



<?php if (!$karyawan): ?>
    <div class="mt-4 alert alert-secondary">
        <strong>Silakan pilih karyawan terlebih dahulu pada bagian search di atas.</strong>
    </div>
<?php else: ?>

    <div class="container my-4">
        <div class="mb-4 card ">
            <div class="d-flex justify-content-between bg-light">

                <div class="card-header ">
                    <strong><?= Html::encode($karyawan['nama']) ?></strong><br>
                    Kode: <?= Html::encode($karyawan['kode_karyawan']) ?><br>
                    Bagian: <?= Html::encode($karyawan['nama_bagian']) ?><br>
                    Jabatan: <?= Html::encode($karyawan['jabatan']) ?><br>
                    Jam Kerja: <?= Html::encode($karyawan['nama_jam_kerja']) ?>
                </div>

            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="text-center thead-dark">
                        <tr>
                            <th>Tanggal</th>
                            <th>Hari</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Total Jam</th>
                            <th>Shift</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($dataAbsensi)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">Data absensi tidak ditemukan.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($dataAbsensi as $entry): ?>

                                <?php
                                $tgl = $entry['tanggal'];
                                $jamMasuk = $entry['jam_masuk'];
                                $jamPulang = $entry['jam_pulang'];

                                $totalJam = '';
                                if ($jamMasuk && $jamPulang && $jamPulang !== '00:00:00') {
                                    $masuk = new DateTime($jamMasuk);
                                    $pulang = new DateTime($jamPulang);
                                    $interval = $masuk->diff($pulang);
                                    $totalJam = $interval->format('%H:%I:%S');
                                }

                                $hari = date('w', strtotime($tgl));
                                $hariNama = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][$hari];
                                ?>
                                <tr<?= $hari === 0 ? ' class="table-secondary"' : '' ?>>
                                    <td><?= Html::encode(date('d-m-Y', strtotime($tgl))) ?></td>
                                    <td><?= Html::encode($hariNama) ?></td>
                                    <td><?= Html::encode($jamMasuk) ?></td>
                                    <td><?= Html::encode($jamPulang) ?></td>
                                    <td><?= Html::encode($totalJam) ?></td>
                                    <td><?= Html::encode($entry['nama_shift'] ?? '-') ?></td>
                                    <td><?= Html::encode($entry['keterangan'] ?: '-') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>