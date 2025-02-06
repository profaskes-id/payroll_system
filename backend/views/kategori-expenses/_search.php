<?php

use backend\models\KategoriExpenses;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\KategoriExpensesSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="kategori-expenses-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-md-9 col-12">
            <?php $nama_group = \yii\helpers\ArrayHelper::map(KategoriExpenses::find()->all(), 'id_kategori_expenses', 'nama_kategori');
            echo $form->field($model, 'id_kategori_expenses')->widget(Select2::classname(), [
                'data' => $nama_group,
                'language' => 'id',
                'options' => ['placeholder' => 'Cari Nama Kategori ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label(false);
            ?>
        </div>
        <div class="col-3">
            <div class="form-group d-flex items-center w-100  justify-content-around">
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