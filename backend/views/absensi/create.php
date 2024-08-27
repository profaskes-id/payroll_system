<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Absensi $model */

$this->title = 'Absensi Masuk';
$this->params['breadcrumbs'][] = ['label' => 'Absensis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Absensis Masuk',];

?>
<div class="absensi-create">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>