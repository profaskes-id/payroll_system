<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

$form = ActiveForm::begin(); ?>

<div class="relative min-h-[85dvh]">

    <div class="mb-5">
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 capitalize">Tanggal</label>
        <?= $form->field($model, 'tanggal')->textInput(['type' => 'date', 'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 '])->label(false) ?>
    </div>

    <div class="mb-5">
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 capitalize">jam mulai</label>
        <?= $form->field($model, 'jam_mulai')->textInput(['type' => 'time', 'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 '])->label(false) ?>
    </div>
    <div class="mb-5">
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 capitalize">jam selesai</label>
        <?= $form->field($model, 'jam_selesai')->textInput(['type' => 'time', 'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 '])->label(false) ?>
    </div>



    <p class="mb-2 text-sm font-medium text-gray-900 capitalize">List Pekerjaan Lembur</p>
    <div id="items-container">
        <?php foreach ($poinArray as $index => $poin) : ?>
            <div class="flex mt-2 item-center space-x-2">
                <input type="text" name="pekerjaan[]" class="border-gray-500 border-1 rounded-md w-[90%]" placeholder="Item <?= $index + 1 ?>" value="<?= Html::encode($poin) ?>">

                <button type="button" class="remove-item p-2 bg-red-500 text-white rounded-md"><svg xmlns='http: //www.w3.org/2000/svg' width='28' height='28' viewBox='0 0 24 24'>
                        <path fill='white' d='M7 21q-.825 0-1.412-.587T5 19V6H4V4h5V3h6v1h5v2h-1v13q0 .825-.587 1.413T17 21zm2-4h2V8H9zm4 0h2V8h-2z' />
                    </svg>
                </button>
            </div>
        <?php endforeach; ?>
    </div>
    <button class="bg-green-500 relative z-40  mt-5 mb-3 text-white block w-full py-3 rounded-md" type="button" id="add-item">Tambah Item</button>
    <div class="h-[80px] w-full"></div>
    <div class=" absolute bottom-0 left-0 right-0">
        <div class="">
            <?= $this->render('@backend/views/components/element/_submit-button', ['text' => 'Submit']); ?>
        </div>
    </div>

</div>

</div>

<?php ActiveForm::end(); ?>


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