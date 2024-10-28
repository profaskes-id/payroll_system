<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanWfhSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pengajuan-wfh-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>


    <?= $form->field($model, 'id_karyawan') ?>

    <?php echo $form->field($model, 'status') ?>

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