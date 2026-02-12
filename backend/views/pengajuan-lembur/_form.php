<?php

use backend\models\Karyawan;
use backend\models\MasterKode;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanLembur $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pengajuan-lembur-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-12">
                    <?php
                    $data = \yii\helpers\ArrayHelper::map(Karyawan::find()->asArray()->where(['is_aktif' => 1])->all(), 'id_karyawan', 'nama');
                    echo $form->field($model, 'id_karyawan')->widget(Select2::classname(), [
                        'data' => $data,
                        'language' => 'id',
                        'options' => ['placeholder' => 'Pilih Karyawan ...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])->label('Karyawan');
                    ?>
                </div>
                <div class="col-12">
                    <?= $form->field($model, 'tanggal')->textInput(['type' => 'date']) ?>
                </div>


                <div class="col-12">
                    <?= $form->field($model, 'jam_mulai')->textInput(['type' => 'time']) ?>
                </div>


                <div class="col-12">
                    <?= $form->field($model, 'jam_selesai')->textInput(['type' => 'time']) ?>
                </div>

                <?php
                if (!$model->isNewRecord) : ?>

                    <div class="col-12">
                        <?= $form->field($model, 'durasi')->textInput(['type' => 'time']) ?>
                    </div>
                <?php endif; ?>
                <?php
                if (!$model->isNewRecord) : ?>

                    <div class="col-12">
                        <?= $form->field($model, 'hitungan_jam')->textInput(['format' => ['decimal', 2]]) ?>
                    </div>
                <?php endif; ?>


                <?php
                if (!$model->isNewRecord) : ?>

                    <div class="col-12">
                        <?= $form->field($model, 'catatan_admin')->textarea(['rows' => 1]) ?>
                    </div>
                <?php endif; ?>



                <div class="col-md-6">
                    <?php
                    $data = \yii\helpers\ArrayHelper::map(MasterKode::find()->where(['nama_group' => Yii::$app->params['status-pengajuan']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all(), 'kode', 'nama_kode');

                    echo $form->field($model, 'status')->radioList($data, [
                        'item' => function ($index, $label, $name, $checked, $value) use ($model) {
                            // Tentukan apakah radio button untuk value 1 harus checked
                            if ($model->isNewRecord) {
                                $isChecked = $value == 1 ? true : $checked;
                            } else {
                                $isChecked = $checked;
                            }

                            return Html::radio($name, $isChecked, [
                                'value' => $value,
                                'label' => $label,
                                'labelOptions' => ['class' => 'radio-label mr-4'],
                            ]);
                        },
                    ])->label('Status Pengajuan');
                    ?>
                </div>





            </div>
        </div>


        <div class="col-md-4">

            <div id="items-container">
                <label for="">List Pekerjaan</label>
                <?php
                $poinArray ??= [];
                foreach ($poinArray as $index => $pekerjaan) : ?>
                    <div class="mt-2 d-flex">
                        <input type="text" name="pekerjaan[]" class="form-control" placeholder="Item <?= $index + 1 ?>" value="<?= Html::encode($pekerjaan) ?>">
                        <button type="button" class="reset-button remove-item">Remove</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="mt-2 tambah-button" type="button" id="add-item">Tambah Item</button>
        </div>

    </div>


    <div class="pt-4 col-md-6">

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


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const addItemButton = document.getElementById("add-item");
        const itemsContainer = document.getElementById("items-container");

        let itemCount = <?= count($poinArray) ?>; // Menghitung jumlah item dari PHP

        addItemButton.addEventListener("click", function() {
            itemCount++; // Menambah jumlah item

            const newItem = document.createElement("div");
            newItem.classList.add("item", "d-flex", 'mt-2');

            const input = document.createElement("input");
            input.type = "text";
            input.name = "pekerjaan[]";
            input.classList.add('form-control');
            input.placeholder = "Item " + itemCount;

            const removeButton = document.createElement("button");
            removeButton.type = "button";
            removeButton.classList.add("remove-item", "btn", "btn-danger");
            removeButton.textContent = "Remove";
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