<?php

use yii\helpers\Html;

$approveCount = 0;

if (empty($pengajuanCuti)): ?>
    <p>Tidak ada pengajuan cuti.</p>
<?php else: ?>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Tanggal <span style="font-size: 9px;">(Pengajuan yang disetujui)</span></th>
                <th style="text-align: center;">Status</th>
                <th style="text-align: center;">Total Hari Digunakan</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pengajuanCuti as $cuti):

            ?>
                <tr>
                    <td>
                        <ul>
                            <?php foreach ($cuti->detailCuti as $detail): ?>
                                <?php if ($detail->status == 1) : ?>
                                    <?php ++$approveCount; ?>
                                    <li><?= Yii::$app->formatter->asDate($detail->tanggal) ?></li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </td>
                    <td style="text-align: center;"><?= Html::encode($cuti->jenisCuti->jenis_cuti) ?></td>
                    <td style="width: 30%; text-align: center"><?php echo $approveCount ?></td>
                </tr>
                <?php $approveCount = 0 ?>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>