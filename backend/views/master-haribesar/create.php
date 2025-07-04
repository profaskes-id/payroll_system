<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\MasterHaribesar $model */

$this->title = Yii::t('app', 'Create Master Hari Libur');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Master Hari Liburs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-haribesar-create">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>