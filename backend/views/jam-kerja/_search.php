<?php

use backend\models\JamKerja;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @varJamKerjaSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="jam-kerja-search">
    <div class="karyawan-search">

        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>

        <div class="row">

            <div class="col-md-9 col-12">
                <?php $nama_group = \yii\helpers\ArrayHelper::map(JamKerja::find()->all(), 'nama_jam_kerja', 'nama_jam_kerja');
                echo $form->field($model, 'nama_jam_kerja')->widget(kartik\select2\Select2::classname(), [
                    'data' => $nama_group,
                    'language' => 'id',
                    'options' => ['placeholder' => 'Cari Nama Jam Kerja ...'],
                    'pluginOptions' => [
                        'tags' => true,
                        'allowClear' => true
                    ],
                ])->label(false);
                ?>
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
</div>