<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\MasterKode $model */

$this->title = 'Tambahkan Master Kode';
$this->params['breadcrumbs'][] = ['label' => 'Master Kode', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-kode-create">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>

</div>