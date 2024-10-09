<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Cetak $model */
/** @var yii\widgets\ActiveForm $form */
?>


<?php
$this->title = Yii::t('app', 'Upload Surat kontrak : {name}', [
    'name' => $model->karyawan->nama,
]);
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


        <div class="mb-5">
            <?php if ($model->surat_upload): ?>
                <a href="<?= Yii::getAlias('@root/panel/' . $model->surat_upload) ?>" target="_blank">
                    preview
                </a>
            <?php else: ?>
                <p>Belum Di Upload</p>
            <?php endif; ?>
        </div>

        <div class="">
            <?= $form->field($model, 'surat_upload')->fileInput(['class' => 'form-control', 'maxlength' => true]) ?>
        </div>
    </div>

    <div class="col-md-6"></div>

    <div class="col-md-6 form-group">
        <button class="add-button" type="submit">
            <span>
                Save
            </span>
        </button>
    </div>
</div>

<?php ActiveForm::end(); ?>