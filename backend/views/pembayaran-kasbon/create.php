<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\PembayaranKasbon $model */

$this->title = 'Create Pembayaran Kasbon';
$this->params['breadcrumbs'][] = ['label' => 'Pembayaran Kasbon', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pembayaran-kasbon-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>