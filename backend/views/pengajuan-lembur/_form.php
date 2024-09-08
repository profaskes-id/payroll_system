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
        <div class="col-md-6">
            <?php
            $data = \yii\helpers\ArrayHelper::map(Karyawan::find()->all(), 'id_karyawan', 'nama');
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
        <div class="col-md-6">
            <?= $form->field($model, 'tanggal')->textInput(['type' => 'date']) ?>
        </div>


        <div class="col-md-6">
            <?= $form->field($model, 'jam_mulai')->textInput(['type' => 'time']) ?>
        </div>


        <div class="col-md-6">
            <?= $form->field($model, 'jam_selesai')->textInput(['type' => 'time']) ?>
        </div>
        <div class="col-md-6">
            <?php
            $data = \yii\helpers\ArrayHelper::map(MasterKode::find()->where(['nama_group' => Yii::$app->params['status-pengajuan']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all(), 'kode', 'nama_kode');
            echo $form->field($model, 'status')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Pengajuan ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Status Pengajuan');
            ?>
        </div>

        <div class="col-md-6">

            <div id="items-container">
                <?php foreach ($poinArray as $index => $pekerjaan) : ?>
                    <div class="d-flex mt-2">
                        <input type="text" name="pekerjaan[]" class="form-control" placeholder="Item <?= $index + 1 ?>" value="<?= Html::encode($pekerjaan) ?>">
                        <button type="button" class="btn btn-danger remove-item">Remove</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="btn btn-success mt-3" type="button" id="add-item">Tambah Item</button>
        </div>


        <div class="col-md-6">

            <div class="form-group">
                <button class="add-button" type="submit">
                    <span>
                        Submit
                    </span>
                </button>
            </div>

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