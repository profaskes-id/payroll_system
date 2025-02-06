<?php

use backend\models\Faq;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\FaqSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="faq-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

<div class="row">
    <div class="col-md-5 col-12">
        <?php 
        $nama_kode = \yii\helpers\ArrayHelper::map(Faq::find()->all(), 'id_faq', 'question');
        echo $form->field($model, 'id_faq')->widget(Select2::classname(), [
            'data' => $nama_kode,
            'language' => 'id',
            'options' => ['placeholder' => 'Cari Pertanyaan ...'],
            'pluginOptions' => [
                'tags' => true,
                'allowClear' => true
            ],
        ])->label(false);
        ?>
    </div>
    <div class="col-md-4 col-12">
        <?= $form->field($model, 'status')->dropDownList([
            1 => 'Aktif',
            0 => 'Tidak Aktif'
        ], [
            'prompt' => 'Pilih Status',
            'class' => 'form-control'
        ])->label(false); ?>
    </div>
    <div class="col-3">
        <div class="items-center form-group d-flex w-100 justify-content-around">
            <button class="add-button" type="submit" data-toggle="collapse" data-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample">
                <i class="fas fa-search"></i>
                <span>
                    Search
                </span>
            </button>

            <a class="reset-button" href="<?= \yii\helpers\Url::to(['index']) ?>">
                <i class="fas fa-undo"></i>
                <span>
                    Reset
                </span>
            </a>
        </div>
    </div>
</div>
    <?php ActiveForm::end(); ?>

</div>