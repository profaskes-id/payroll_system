<?php

use app\restapi\modules\v1\models\Kategori;
use backend\models\ExpensesDetail;
use backend\models\KategoriExpenses;
use backend\models\SubkategoriExpenses;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\ExpensesDetailSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="expenses-detail-search">


    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'post',
    ]); ?>

    <div class="row">

        <div class="col-md-3 col-12">
            <?php $nama_kode = \yii\helpers\ArrayHelper::map(KategoriExpenses::find()->all(), 'id_kategori_expenses', 'nama_kategori');
            echo $form->field($model, 'id_kategori_expenses')->widget(Select2::classname(), [
                'data' => $nama_kode,
                'language' => 'id',
                'options' => ['placeholder' => 'Cari berdasarkan kategori ...'],
                'pluginOptions' => [
                    'tags' => true,
                    'allowClear' => true
                ],
            ])->label('Kategori');
            ?>
        </div>
        <div class="col-md-3 col-12">
            <?php $nama_kode = \yii\helpers\ArrayHelper::map(SubkategoriExpenses::find()->all(), 'id_subkategori_expenses', 'nama_subkategori');
            echo $form->field($model, 'id_subkategori_expenses')->widget(Select2::classname(), [
                'data' => $nama_kode,
                'language' => 'id',
                'options' => ['placeholder' => 'Cari berdasarkan subkategori ...'],
                'pluginOptions' => [
                    'tags' => true,
                    'allowClear' => true
                ],
            ])->label('Subkategori');
            ?>
        </div>
        <div class="col-12 col-md-3">
            <?= $form->field($model, 'tanggal_mulai')->textInput(['type' => 'date', 'value' => $tgl_mulai])->label("Mulai Dari") ?>
        </div>
        <div class="col-12 col-md-3">
            <?= $form->field($model, 'tanggal_selesai')->textInput(['type' => 'date', 'value' => $tgl_selesai])->label("Sampai Dengan") ?>
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