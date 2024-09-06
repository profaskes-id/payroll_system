<?php

use backend\models\MasterKode;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Absensi $model */

$this->title = 'Create Absensi';
$this->params['breadcrumbs'][] = ['label' => 'Absensis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


$izin = MasterKode::find()->where(['nama_group' => 'status-hadir'])->andWhere(['!=', 'nama_kode', 'Hadir'])->orderBy(['urutan' => SORT_ASC])->all();

?>



<div class="max-w-[500px] mx-auto px-5 lg:px-8">



    <?= $this->render('@backend/views/components/_header', ['title' => 'Absensi']); ?>
    <section class="grid grid-cols-12 justify-center mt-2 gap-y-10">



        <div class="col-span-12">

            <?php $form = ActiveForm::begin([
                'id' => 'form-absensi',
                'options' => ['enctype' => 'multipart/form-data'],
            ]); ?>

            <div class="grid grid-cols-12 gap-y-6">
                <div class="col-span-12">
                    <fieldset class="grid grid-cols-2 gap-4">
                        <legend class="col-span-12 block mb-2 text-sm font-medium text-gray-900 ">Status</legend>

                        <?php foreach ($izin as $key => $value) : ?>
                            <div>
                                <label
                                    for="<?= $value->nama_kode ?>"
                                    class="block cursor-pointer rounded-lg border border-gray-100 bg-white p-4 text-sm font-medium shadow-sm hover:border-gray-200 has-[:checked]:border-blue-500 has-[:checked]:ring-1 has-[:checked]:ring-blue-500">
                                    <div>

                                        <p class="mt-1 text-gray-900"><?= $value->nama_kode ?></p>
                                    </div>

                                    <input
                                        type="radio"
                                        name="statusHadir"
                                        id="<?= $value->nama_kode ?>"
                                        value="<?= $value->kode ?>"
                                        class="sr-only" />
                                </label>
                            </div>
                        <?php endforeach ?>

                    </fieldset>
                </div>

                <label for="message" class="col-span-12 block mb-2 text-sm font-medium text-gray-900 ">
                    <span class="block pb-2">Keterangan</span>
                    <?= $form->field($model, 'keterangan')->textarea(['rows' => 12, 'maxlength' => true, 'class' => 'block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 '])->label(false) ?>
                </label>

                <label class="col-span-12 block mb-2 text-sm font-medium text-gray-900 " for="file_input">
                    <span class="block pb-2">Upload Lampiran</span>
                    <?= $form->field($model, 'lampiran')->fileInput(['class' => 'block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer  bg-gray-50  py-2  file:py-1 file:mx-0 file:bg-white file:border-0 file:text-sm file:font-semibold file:cursor-pointer file:text-gray-700 hover:file:bg-gray-100 focus:outline-none   '])->label(false) ?>
                </label>



                <div class="col-span-12">
                    <div class="">
                        <?= $this->render('@backend/views/components/element/_submit-button', ['text' => 'Submit']); ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>

            </div>
    </section>



</div>