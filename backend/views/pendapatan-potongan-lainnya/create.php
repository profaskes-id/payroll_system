<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\PendapatanPotonganLainnya $model */

$request = Yii::$app->request;
$get = $request->get();
// Cek apakah parameter ada
if (isset($get['pendapatan']) && $get['pendapatan'] == "1") {
    $this->title = "Tambahkan PENDAPATAN Lainnya";
} elseif (isset($get['potongan']) && $get['potongan'] == "1") {
    $this->title = "Tambahkan POTONGAN Lainnya";
}

$this->params['breadcrumbs'][] = ['label' => 'Transaksi Gaji', 'url' => ['/transaksi-gaji/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pendapatan-potongan-lainnya-create">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['/transaksi-gaji/index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>