<?php

use backend\models\helpers\KaryawanHelper;
use backend\models\Karyawan;
use backend\models\MasterKode;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanLembur $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="relative p-2 bg-white rounded-lg shadow-md md:p-6">

    <?php $form = ActiveForm::begin(); ?>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

        <!-- Karyawan Field -->
        <div class="">
            <p class="block mb-2 text-sm font-medium text-gray-900 capitalize ">Karyawan</p>
            <?php
            $data = \yii\helpers\ArrayHelper::map(KaryawanHelper::getKaryawanData(), 'id_karyawan', 'nama');
            echo $form->field($model, 'id_karyawan')->dropDownList(
                $data,
                [
                    'disabled' => true,
                    'prompt' => 'Pilih Karyawan ...',
                    'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 '
                ]
            )->label(false);
            ?>
        </div>


        <div class="row-span-2 ">
            <p class="block mb-2 text-sm font-medium text-gray-900 capitalize ">Pekerjaan</p>
            <?php foreach ($poinArray as $index => $poin) : ?>
                <div class="flex mt-2 space-x-2 item-center">
                    <input type="text" name="pekerjaan[]" class="border-gray-500 border-1 rounded-md w-[90%]" placeholder="Item <?= $index + 1 ?>" value="<?= Html::encode($poin) ?>">

                    <button type="button" class="p-2 text-white bg-red-500 rounded-md remove-item"><svg xmlns='http: //www.w3.org/2000/svg' width='28' height='28' viewBox='0 0 24 24'>
                            <path fill='white' d='M7 21q-.825 0-1.412-.587T5 19V6H4V4h5V3h6v1h5v2h-1v13q0 .825-.587 1.413T17 21zm2-4h2V8H9zm4 0h2V8h-2z' />
                        </svg>
                    </button>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Tanggal Field -->
        <div>
            <?= $form->field($model, 'tanggal')->textInput([
                'type' => 'date',
                'class' => 'w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200'
            ])->label('Tanggal') ?>
        </div>

        <!-- Jam Mulai Field -->
        <div>
            <?= $form->field($model, 'jam_mulai')->textInput([
                'type' => 'time',
                'class' => 'w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200'
            ])->label('Jam Mulai') ?>
        </div>

        <!-- Jam Selesai Field -->
        <div>
            <?= $form->field($model, 'jam_selesai')->textInput([
                'type' => 'time',
                'class' => 'w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200'
            ])->label('Jam Selesai') ?>
        </div>

        <!-- Durasi Field (hanya jika bukan record baru) -->
        <?php if (!$model->isNewRecord) : ?>
            <div>
                <?= $form->field($model, 'durasi')->textInput([
                    'format' => 'time',
                    'class' => 'w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200'
                ])->label('Durasi') ?>
            </div>
        <?php endif; ?>

        <!-- Hitungan Jam Field (hanya jika bukan record baru) -->
        <?php if (!$model->isNewRecord) : ?>
            <div>
                <?= $form->field($model, 'hitungan_jam')->textInput([
                    'format' => ['decimal', 2],
                    'class' => 'w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200'
                ])->label('Hitungan Jam') ?>
            </div>
        <?php endif; ?>


        <div class="col-12">

            <?php
            $data = \yii\helpers\ArrayHelper::map(MasterKode::find()->where(['nama_group' => Yii::$app->params['status-pengajuan']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all(), 'kode', 'nama_kode');


            echo $form->field($model, 'status')->radioList($data, [
                'item' => function ($index, $label, $name, $checked, $value) use ($model) {

                    $isChecked = $value == 1 ? true : $checked;


                    return Html::radio($name, $isChecked, [
                        'value' => $value,
                        'label' => $label,
                        'labelOptions' => ['class' => 'radio-label mr-4'],
                    ]);
                },
            ])->label('Status Pengajuan');
            ?>
        </div>


        <!-- Catatan Admin Field (hanya jika bukan record baru) -->
        <?php if (!$model->isNewRecord): ?>
            <div class="">
                <?= $form->field($model, 'catatan_admin')->textarea([
                    'rows' => 2,
                    'class' => 'w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500'
                ]) ?>
            </div>
        <?php endif; ?>

        <div class="">
            <button class="px-4 py-2 font-bold text-white transition duration-200 bg-blue-500 rounded-lg hover:bg-blue-700" type="submit">
                Save
            </button>
        </div>

    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const addItemButton = document.getElementById("add-item");
        const itemsContainer = document.getElementById("items-container");

        let itemCount = <?= count($poinArray) ?>; // Menghitung jumlah item dari PHP

        addItemButton.addEventListener("click", function() {
            itemCount++; // Menambah jumlah item

            const newItem = document.createElement("div");
            newItem.classList.add("item", "flex", 'space-x-2', 'mt-2');

            const input = document.createElement("textarea");
            input.rows = "1";
            input.name = "pekerjaan[]";
            input.classList.add('w-[90%]', 'border-gray', 'rounded-md');
            input.placeholder = "Pekerjaan";

            const removeButton = document.createElement("button");
            removeButton.type = "button";
            removeButton.classList.add("remove-item", "p-2", "bg-red-500", 'text-white', 'rounded-md');
            removeButton.innerHTML = "<svg xmlns='http: //www.w3.org/2000/svg' width='28' height='28' viewBox='0 0 24 24'><path fill='white' d='M7 21q-.825 0-1.412-.587T5 19V6H4V4h5V3h6v1h5v2h-1v13q0 .825-.587 1.413T17 21zm2-4h2V8H9zm4 0h2V8h-2z'/></svg>";
            removeButton.addEventListener("click", function() {
                newItem.remove(); // Menghapus item saat tombol "Remove" ditekan
            });

            newItem.appendChild(input);
            newItem.appendChild(removeButton);
            itemsContainer.appendChild(newItem);
        });

        // Menghapus item saat tombol "Remove" ditekan (untuk item yang sudah ada)
        itemsContainer.addEventListener("click", function(event) {
            if (event.target.classList.contains("remove-item")) {
                event.target.parentNode.remove();
                itemCount--; // Mengurangi jumlah item saat item dihapus
            }
        });
    });
</script>