<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Tunjangan $model */

$this->title = Yii::t('app', 'Create Tunjangan');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tunjangan'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tunjangan-create">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>