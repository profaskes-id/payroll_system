<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Faq $model */

$this->title = Yii::t('app', 'Update FAQ');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Faqs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_faq, 'url' => ['view', 'id_faq' => $model->id_faq]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="faq-update">

<div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
</div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
