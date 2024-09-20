<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\IzinPulangCepat $model */

$this->title = Yii::t('app', 'Create Izin Pulang Cepat');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Izin Pulang Cepat'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="izin-pulang-cepat-create">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>

        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>

    </div>
</div>