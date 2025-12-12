<?php

use backend\models\helpers\KaryawanHelper;
use backend\models\Karyawan;
use backend\models\MasterKode;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\IzinPulangCepatSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="izin-pulang-cepat-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">

        <div class="col-5">
            <?php $nama_group = \yii\helpers\ArrayHelper::map(KaryawanHelper::getKaryawanData(), 'id_karyawan', 'nama');
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
        <div class="col-md-4 col-6">
            <?php $nama_kode = \yii\helpers\ArrayHelper::map(MasterKode::find()->where(['nama_group' => Yii::$app->params['status-pengajuan']])->all(), 'kode', 'nama_kode');
            echo $form->field($model, 'status')->widget(\kartik\select2\Select2::classname(), [
                'data' => $nama_kode,
                'language' => 'id',
                'options' => ['placeholder' => 'Cari Status Pengajuan ...'],
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