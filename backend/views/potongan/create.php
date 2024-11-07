<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Potongan $model */

$this->title = Yii::t('app', 'Tambah Potongan');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Potongan'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="potongan-create">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>