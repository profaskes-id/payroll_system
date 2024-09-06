<?php

use backend\models\Karyawan;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanDinasSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pengajuan-dinas-search">
    <div class="karyawan-search">

        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>

        <div class="row">
            <div class="col-9">
                <?php $nama_group = \yii\helpers\ArrayHelper::map(Karyawan::find()->all(), 'id_karyawan', 'nama');
                echo $form->field($model, 'id_karyawan')->widget(kartik\select2\Select2::classname(), [
                    'data' => $nama_group,
                    'language' => 'id',
                    'options' => ['placeholder' => 'Cari Karyawan ...'],
                    'pluginOptions' => [
                        'tags' => true,
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
</div>