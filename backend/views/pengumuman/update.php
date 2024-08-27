<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Pengumuman $model */

$this->title = 'Update Pengumuman: ' . $model->id_pengumuman;
$this->params['breadcrumbs'][] = ['label' => 'Pengumuman', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_pengumuman, 'url' => ['view', 'id_pengumuman' => $model->id_pengumuman]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pengumuman-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>