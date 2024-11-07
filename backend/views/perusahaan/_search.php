<?php

use backend\models\MasterKode;
use backend\models\Perusahaan;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PerusahaanSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="perusahaan-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-md-5 col-6">
            <?php $nama_group = \yii\helpers\ArrayHelper::map(Perusahaan::find()->asArray()->all(), 'nama_perusahaan', 'nama_perusahaan');
            echo $form->field($model, 'nama_perusahaan')->widget(Select2::classname(), [
                'data' => $nama_group,
                'language' => 'id',
                'options' => ['placeholder' => 'Cari Nama Perusahaan ...'],
                'pluginOptions' => [
                    'tags' => true,
                    'allowClear' => true
                ],
            ])->label(false);
            ?>
        </div>
        <div class="col-md-4 col-6">
            <?php $nama_kode = \yii\helpers\ArrayHelper::map(MasterKode::find()->asArray()->where(['nama_group' => Yii::$app->params['status-perusahaan']])->all(), 'kode', 'nama_kode');
            echo $form->field($model, 'status_perusahaan')->widget(Select2::classname(), [
                'data' => $nama_kode,
                'language' => 'id',
                'options' => ['placeholder' => 'Cari Status Perusahaan ...'],
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