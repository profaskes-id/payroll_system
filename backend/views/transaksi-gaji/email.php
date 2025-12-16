<?php

use yii\helpers\Html;
?>
<p>Halo <?= Html::encode($karyawan['nama']) ?>,</p>

<p>Berikut terlampir slip gaji Anda untuk bulan <b><?= date('F Y') ?></b>.</p>

<p>Silakan periksa lampiran PDF untuk detail gaji Anda.</p>

<p>Terima kasih,<br>
    <?= Yii::$app->params['APPLICATION_ADMIN'] ?></p>