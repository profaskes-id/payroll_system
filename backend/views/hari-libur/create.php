<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\HariLibur $model */

$this->title = 'Create Hari Libur';
$this->params['breadcrumbs'][] = ['label' => 'Hari Liburs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hari-libur-create">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>