<?php

use backend\models\MasterKode;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\IzinPulangCepat $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="izin-pulang-cepat-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_karyawan')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'alasan')->textarea(['rows' => 6, 'disabled' => true]) ?>

    <hr>

    <div class="row">
        <div class="col-12">
            <?php echo $form->field($model, 'catatan_admin')->textarea(['rows' => 6,])->label('Catatan Admin (opsional)') ?>
        </div>


        <div class="col-12  ">
            <?php
            $data = \yii\helpers\ArrayHelper::map(MasterKode::find()->where(['nama_group' => Yii::$app->params['status-pengajuan']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all(), 'kode', 'nama_kode');

            echo $form->field($model, 'status')->radioList($data, [
                'item' => function ($index, $label, $name, $checked, $value) {
                    return Html::radio($name, $checked, [
                        'value' => $value,
                        'label' => $label,
                        'labelOptions' => ['class' => 'radio-label mr-4'],
                    ]);
                },
            ])->label('Status Pengajuan');
            ?>
        </div>

        <div class="col-12  ">

            <div class="form-group">
                <button class="add-button" type="submit">
                    <span>
                        Save
                    </span>
                </button>
            </div>

        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>