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



<div class="relative z-40 w-full px-5 mx-auto lg:px-8">



    <?= $this->render('@backend/views/components/_header', ['link' => '/panel/home/absen-masuk', 'title' => 'Izin Pulang Cepat']); ?>
    <section class="grid justify-center grid-cols-12 mt-2 gap-y-10">



        <div class="col-span-12 relative h-[90dvh] pt-2">

            <?php $form = ActiveForm::begin([
                'id' => 'form-absensi',
            ]); ?>



            <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alasan Pulang Cepat</label>

            <?= $form->field($model, 'alasan')->textarea(['id' => 'message', 'rows' => 12, 'class' => 'block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600'])->label(false) ?>
            <div class="absolute left-0 right-0 col-span-12 bottom-3">
                <div class="">
                    <?= $this->render('@backend/views/components/element/_submit-button', ['text' => 'Submit']); ?>
                </div>
            </div>




            <?php ActiveForm::end(); ?>

        </div>
    </section>



</div>