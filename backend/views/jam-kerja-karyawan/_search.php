<?php

use backend\models\JamKerja;
use backend\models\MasterKode;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\JamKerjaKaryawanSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="jam-kerja-karyawan-search">
    <div class="karyawan-search">

        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>

        <div class="row">
            <div class="col-5">
                <?php $nama_group = \yii\helpers\ArrayHelper::map(JamKerja::find()->all(), 'id_jam_kerja', 'nama_jam_kerja');
                echo $form->field($model, 'id_jam_kerja')->widget(kartik\select2\Select2::classname(), [
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
            <div class="col-4">
                <?php $nama_group = \yii\helpers\ArrayHelper::map(MasterKode::find()->where(['nama_group' => Yii::$app->params['jenis-shift']])->andWhere(['!=', 'status', 0])->all(), 'kode', 'nama_kode');
                echo $form->field($model, 'jenis_shift')->widget(kartik\select2\Select2::classname(), [
                    'data' => $nama_group,
                    'language' => 'id',
                    'options' => ['placeholder' => 'Cari Jenis Shift ...'],
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