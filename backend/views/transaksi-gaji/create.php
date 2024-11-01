<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\TransaksiGaji $model */

$this->title = Yii::t('app', 'Tambah Transaksi Gaji');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Transaksi Gaji'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaksi-gaji-create">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
        'rekapandata' => $rekapandata,

    ]) ?>

</div>