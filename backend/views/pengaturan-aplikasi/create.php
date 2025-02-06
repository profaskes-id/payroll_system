<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\PengaturanAplikasi $model */

$this->title = Yii::t('app', 'Create Pengaturan Aplikasi');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pengaturan Aplikasis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengaturan-aplikasi-create">

<div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
