<?php

use backend\models\MasterKode;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Absensi $model */

$this->title = 'Create Absensi';
$this->params['breadcrumbs'][] = ['label' => 'Absensis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


$izin = MasterKode::find()->where(['nama_group' => 'status-hadir'])->all();

?>



<div class="max-w-[500px] mx-auto sm:px-6 lg:px-8">



    <section class="grid grid-cols-12 justify-center p-5 gap-y-10">
        <div class="col-span-12  w-full   px-5 pt-5  ">
            <div class="flex justify-between items-center">
                <div class="flex items-start justify-center flex-col text-2xl">
                    <?= $this->render('@backend/views/components/fragment/_back-button'); ?>
                </div>
                <div>
                    <div class="w-[50px] h-[50px] rounded-full bg-[url(https://plus.unsplash.com/premium_photo-1664870883044-0d82e3d63d99?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D)] bg-cover bg-center">
                    </div>
                </div>
            </div>
        </div>


        <div class="col-span-12">

            <?php $form = ActiveForm::begin([
                'id' => 'form-absensi',
                'options' => ['enctype' => 'multipart/form-data'],
            ]); ?>





            <div class="grid grid-cols-12 gap-y-6">


                <div class="col-span-12">
                    <fieldset class="grid grid-cols-2 gap-4">
                        <legend class="col-span-12 block mb-2 text-sm font-medium text-gray-900 ">Status</legend>
                        <div>
                            <label
                                for="DeliveryStandard"
                                class="block cursor-pointer rounded-lg border border-gray-100 bg-white p-4 text-sm font-medium shadow-sm hover:border-gray-200 has-[:checked]:border-blue-500 has-[:checked]:ring-1 has-[:checked]:ring-blue-500">
                                <div>

                                    <p class="mt-1 text-gray-900"><?php echo $izin[1]->nama_kode ?></p>
                                </div>

                                <input
                                    type="radio"
                                    name="statusHadir"
                                    id="DeliveryStandard"
                                    value="<?php echo $izin[1]->kode ?>"
                                    class="sr-only" />
                            </label>
                        </div>

                        <div>
                            <label
                                for="DeliveryPriority"
                                class="block cursor-pointer rounded-lg border border-gray-100 bg-white p-4 text-sm font-medium shadow-sm hover:border-gray-200 has-[:checked]:border-blue-500 has-[:checked]:ring-1 has-[:checked]:ring-blue-500">
                                <div>
                                    <p class="mt-1 text-gray-900"><?php echo $izin[2]->nama_kode ?></p>
                                </div>

                                <input
                                    type="radio"
                                    name="statusHadir"
                                    id="DeliveryPriority"
                                    value="<?php echo $izin[2]->nama_kode ?>"
                                    class="sr-only" />
                            </label>
                        </div>
                    </fieldset>
                </div>

                <label for="message" class="col-span-12 block mb-2 text-sm font-medium text-gray-900 ">
                    <span class="block pb-2">Keterangan</span>
                    <?= $form->field($model, 'keterangan')->textarea(['rows' => 2, 'maxlength' => true, 'class' => 'block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 '])->label(false) ?>
                </label>

                <label class="col-span-12 block mb-2 text-sm font-medium text-gray-900 " for="file_input">
                    <span class="block pb-2">Upload Lampiran</span>
                    <?= $form->field($model, 'lampiran')->fileInput(['class' => 'block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50  py-2  file:py-1 file:mx-2 file:bg-white file:border-0 file:text-sm file:font-semibold file:cursor-pointer file:text-gray-700 hover:file:bg-gray-100 focus:outline-none   '])->label(false) ?>
                </label>




                <div class="form-group col-span-12">
                    <?= Html::submitButton('Kirim', ['class' => 'add-button']) ?>
                </div>

            </div>
            <?php ActiveForm::end(); ?>

        </div>
    </section>



</div>