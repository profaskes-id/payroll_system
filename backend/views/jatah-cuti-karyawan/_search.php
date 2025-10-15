<?php

use backend\models\helpers\KaryawanHelper;
use backend\models\MasterCuti;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\JatahCutiKaryawanSearch $model */
/** @var yii\widgets\ActiveForm $form */

$model->id_master_cuti =  1;
$model->tahun =  $tahun ?? date('Y');
?>


<div class="jatah-cuti-karyawan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-md-4 col-12">
            <?php $nama_group = \yii\helpers\ArrayHelper::map(KaryawanHelper::getKaryawanData(), 'id_karyawan', 'nama');
            echo $form->field($model, 'id_karyawan')->widget(kartik\select2\Select2::classname(), [
                'data' => $nama_group,
                'language' => 'id',
                'options' => ['placeholder' => 'Cari karyawan ...'],
                'pluginOptions' => [
                    'tags' => true,
                    'allowClear' => true
                ],
            ])->label('karyawan');
            ?>
        </div>
        <div class="col-md-4 col-12">
            <?php $nama_group_cuti = \yii\helpers\ArrayHelper::map(MasterCuti::find()->asArray()->all(), 'id_master_cuti', 'jenis_cuti');
            echo $form->field($model, 'id_master_cuti')->widget(kartik\select2\Select2::classname(), [
                'data' => $nama_group_cuti,
                'language' => 'id',
                'options' => ['placeholder' => 'Cari Jenis Cuti ...'],
                'pluginOptions' => [
                    'tags' => true,
                    'allowClear' => true
                ],
            ])->label('Jenis Cuti');
            ?>
        </div>
        <div class="col-md-4 col-12">
            <?= $form->field($model, 'tahun')->textInput(['type' => 'number', 'maxlength' => true,]) ?>
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
</div>
<?php ActiveForm::end(); ?>