<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\JamKerja $model */

$this->title = 'Tambah Jam Kerja';
$this->params['breadcrumbs'][] = ['label' => 'Jam kerja', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jam-kerja-create">



    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>