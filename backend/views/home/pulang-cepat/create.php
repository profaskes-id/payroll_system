<?php

use backend\models\MasterKode;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Absensi $model */

$this->title = 'Create Absensi';
$this->params['breadcrumbs'][] = ['label' => 'Absensi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>



<div class="max-w-[500px] mx-auto px-5 lg:px-8">



    <?= $this->render('@backend/views/components/_header', ['title' => 'Izin Pulang Cepat']); ?>
    <section class="grid grid-cols-12 justify-center mt-2 gap-y-10">



        <div class="col-span-12 relative h-[90dvh] pt-2">

            <?php $form = ActiveForm::begin([
                'id' => 'form-absensi',
            ]); ?>



            <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alasan Pulang Cepat</label>

            <?= $form->field($model, 'alasan')->textarea(['id' => 'message', 'rows' => 12, 'class' => 'block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600'])->label(false) ?>
            <div class="col-span-12 absolute bottom-3 left-0 right-0">
                <div class="">
                    <?= $this->render('@backend/views/components/element/_submit-button', ['text' => 'Submit']); ?>
                </div>
            </div>


            <?php ActiveForm::end(); ?>

        </div>
    </section>



</div>