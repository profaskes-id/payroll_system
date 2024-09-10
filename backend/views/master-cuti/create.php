<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\MasterCuti $model */

$this->title = 'Tambah Master Cuti';
$this->params['breadcrumbs'][] = ['label' => 'Master Cuti', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-cuti-create">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>