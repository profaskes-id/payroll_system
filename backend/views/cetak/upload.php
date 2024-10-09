<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Cetak $model */
/** @var yii\widgets\ActiveForm $form */
?>


<?php
$this->title = Yii::t('app', 'Surat Perjanjian Kontrak Kerja');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cetaks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->karyawan->nama, 'url' => ['view', 'id_cetak' => $model->id_cetak]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<style>
    tr td:first-child {
        width: 150px;
    }
</style>


<div class="costume-container">
    <p class="">
        <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['/karyawan/view', 'id_karyawan' => $model->id_karyawan], ['class' => 'costume-btn']) ?>
    </p>
</div>




<?php $form = ActiveForm::begin(); ?>

<div class=" table-container">
    <div class="">

        <?php if ($model->surat_upload): ?>
            <h5>Perbarui Surat Perjanjian Kontrak Kerja ( <?= $model->karyawan->nama ?> )</h5>
        <?php else: ?>
            <h5>Upload Surat Perjanjian Kontrak Kerja</h5>
        <?php endif; ?>
        <div class="">
            <?= $form->field($model, 'surat_upload')->fileInput(['class' => 'form-control', 'maxlength' => true])->label('Surat Kontrak Kerja') ?>
        </div>


        <?php if ($model->surat_upload): ?>
            <div class="col-md-6 form-group">
                <button class="add-button" type="submit">
                    <span>
                        Update
                    </span>
                </button>
            </div>
        <?php else: ?>
            <div class="col-md-6 form-group">
                <button class="add-button" type="submit">
                    <span>
                        Save
                    </span>
                </button>
            </div> <?php endif; ?>

    </div>


</div>
<div class='table-container mt-5'>

    <div class="mb-5">
        <h5>Surat Perjanjian Kontrak Kerja <?= $model->karyawan->nama ?></h5>
        <?php if ($model->surat_upload): ?>
            <a href="<?= Yii::getAlias('@root/panel/' . $model->surat_upload) ?>" target="_blank">
                Klik Untuk Lihat Surat Kontrak
            </a>
        <?php else: ?>
            <p>Belum Di Upload</p>
        <?php endif; ?>
    </div>
</div>

<?php ActiveForm::end(); ?>