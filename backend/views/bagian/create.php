<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Bagian $model */

$this->title = 'Tabmah Bagian';
$this->params['breadcrumbs'][] = ['label' => 'bagian', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bagian-create">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>