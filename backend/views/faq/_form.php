<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Faq $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="faq-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'question')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'answer')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->radioList([
        0 => 'Tidak Aktif',
    1 => 'Aktif', 
], [
    'itemOptions' => [
        'style' => 'margin-left: 20px; display: inline-block;' // Menambahkan jarak dan menampilkan secara inline
    ]
])->label('Status'); ?>
    <div class="form-group">
        <button class="add-button" type="submit">
            <span>
                Save
            </span>
        </button>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
