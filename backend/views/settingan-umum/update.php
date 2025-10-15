<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\SettinganUmum $model */

$this->title = Yii::t('app', 'Update Settingan Lainnya');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Settingan Lainnya'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->kode_setting, 'url' => ['view', 'id_settingan_umum' => $model->id_settingan_umum]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="settingan-umum-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>