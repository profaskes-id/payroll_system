<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Faq $model */

$this->title = Yii::t('app', 'Tambah FAQ');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Faqs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="faq-create">

<div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
</div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
