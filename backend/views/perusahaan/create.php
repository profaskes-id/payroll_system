<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Perusahaan $model */

$this->title = 'Tabmah perusahaan';
$this->params['breadcrumbs'][] = ['label' => 'perusahaan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="perusahaan-create">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>