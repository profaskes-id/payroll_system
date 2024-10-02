<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\AbsensiSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="absensi-search">

        <?php $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
        ]); ?>


        <!-- <?php // $form->field($model, 'id_karyawan') 
                ?> -->


        <!-- <?php // $form->field($model, 'tanggal') 
                ?> -->


        <?php // echo $form->field($model, 'jam_masuk') 
        ?>

        <?php // echo $form->field($model, 'jam_pulang') 
        ?>

        <?php // echo $form->field($model, 'kode_status_hadir') 
        ?>

        <!-- <div classphp //"form-group"> -->
        <!-- <?php // Html::submitButton('Search', ['class' php //> 'btn btn-primary']) 
                ?> -->
        <!--  <?php // Html::resetButton('Reset', ['class' php //> 'btn btn-outline-secondary']) 
                ?> -->
</div>

<?php ActiveForm::end(); ?>

</div>