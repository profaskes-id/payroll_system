<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\PendapatanPotonganLainnya $model */

$this->title = 'Update Pendapatan Potongan Lainnya: ' . $model->id_ppl;
$this->params['breadcrumbs'][] = ['label' => 'Pendapatan Potongan Lainnyas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_ppl, 'url' => ['view', 'id_ppl' => $model->id_ppl]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pendapatan-potongan-lainnya-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['/transaksi-gaji/index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>