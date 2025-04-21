<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\SettinganUmum $model */

$this->title = Yii::t('app', 'Create Settingan Umum');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Settingan Umums'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="settingan-umum-create">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>