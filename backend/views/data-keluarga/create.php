<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\DataKeluarga $model */

$this->title = 'Tambah Data Keluarga';
$this->params['breadcrumbs'][] = ['label' => 'Data keluarga', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="data-keluarga-create">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>