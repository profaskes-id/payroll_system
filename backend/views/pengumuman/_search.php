<?php

use backend\models\Pengumuman;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengumumanSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pengumuman-search">

    <div class="karyawan-search">

        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>

        <div class="row">
            <div class="col-md-5 col-6">
                <?php $nama_group = \yii\helpers\ArrayHelper::map(Pengumuman::find()->all(), 'judul', 'judul');
                echo $form->field($model, 'judul')->widget(kartik\select2\Select2::classname(), [
                    'data' => $nama_group,
                    'language' => 'id',
                    'options' => ['placeholder' => 'Cari Jam kerja ...'],
                    'pluginOptions' => [
                        'tags' => true,
                        'allowClear' => true
                    ],
                ])->label(false);
                ?>
            </div>
            <div class="col-md-4 col-6">

                <?= $form->field($model, 'dibuat_pada')->textInput(['type' => 'date'])->label(false); ?>

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
</div>