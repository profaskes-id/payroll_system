<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\MasterGaji $model */

$this->title = Yii::t('app', 'Tambah Data Master Gaji');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Master Gaji'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-gaji-create">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>