<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\KategoriExpenses $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="kategori-expenses-form table-container">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">

        <div class="col-12">
            <?= $form->field($model, 'nama_kategori')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-12">
            <?= $form->field($model, 'deskripsi')->textarea(['rows' => 6]) ?>
        </div>
    </div>

    <div class="form-group">
        <button class="add-button" type="submit">
            <span>
                Save
            </span>
        </button>
    </div>

    <?php ActiveForm::end(); ?>

</div>