<?php

use backend\models\MasterLokasi;
use backend\models\Perusahaan;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Bagian $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="bagian-form table-container">

    <?php

    $id_perusahaan = Yii::$app->request->get('id_perusahaan') ?? $model->id_perusahaan;
    ?>

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-12">
            <?= $form->field($model, 'nama_bagian')->textInput(['maxlength' => true]) ?>
        </div>
        <?= $form->field($model, 'id_perusahaan')->hiddenInput(['value' => $id_perusahaan, 'maxlength' => true])->label(false) ?>

    </div>


    <div class="form-group">
        <button class="add-button" type="submit">
            <span>
                Save
            </span>
        </button>
    </div>
</div>
<?php ActiveForm::end(); ?>
</div>