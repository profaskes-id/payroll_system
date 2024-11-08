<?php

use backend\models\Tunjangan;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\GajiTunjanganSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="gaji-tunjangan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>


    <div class="row">

        <div class="col-md-9 col-12">
            <?php

            $nama_kode = \yii\helpers\ArrayHelper::map(Tunjangan::find()->asArray()->all(), 'id_tunjangan', 'nama_tunjangan');
            echo $form->field($model, 'nama_tunjangan')->widget(Select2::classname(), [
                'data' => $nama_kode,
                'language' => 'id',
                'options' => ['placeholder' => 'Cari nama tunjangan'],
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