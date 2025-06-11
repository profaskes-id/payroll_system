<?php

use yii\helpers\Html;

if (empty($pengajuanCuti)): ?>
    <p>Tidak ada pengajuan cuti.</p>
<?php else: ?>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Status</th>
                <th>Keterangan</th>

            </tr>
        </thead>
        <tbody>
            <?php foreach ($pengajuanCuti as $cuti):
                $start = new DateTime($cuti->tanggal_mulai);
                $end = new DateTime($cuti->tanggal_selesai);
                $diff = $start->diff($end);
                $totalHari = $diff->days + 1; // +1 supaya tanggal sama dihitung 1 hari
            ?>
                <tr>
                    <td><?= Yii::$app->formatter->asDate($cuti->tanggal_mulai) ?></td>
                    <td><?= Yii::$app->formatter->asDate($cuti->tanggal_selesai) ?></td>
                    <td><?= Html::encode($cuti->jenisCuti->jenis_cuti) ?></td>
                    <td><?= Html::encode($cuti->catatan_admin ?: $cuti->alasan_cuti) ?></td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>