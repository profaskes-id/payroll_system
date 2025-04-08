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
        <?php if (empty($poinArray)) : ?>
            <!-- Jika array kosong, tampilkan satu input kosong -->
            <div class="flex mt-2 space-x-2 item">
                <textarea name="pekerjaan[]" rows="1" class="w-[90%] border-gray rounded-md" placeholder="Pekerjaan"></textarea>
                <button type="button" class="p-2 text-white bg-red-500 rounded-md remove-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24">
                        <path fill="white" d="M7 21q-.825 0-1.412-.587T5 19V6H4V4h5V3h6v1h5v2h-1v13q0 .825-.587 1.413T17 21zm2-4h2V8H9zm4 0h2V8h-2z" />
                    </svg>
                </button>
            </div>
        <?php else : ?>
            <!-- Jika ada data di poinArray, tampilkan item-item tersebut -->
            <?php foreach ($poinArray as $index => $poin) : ?>
                <div class="flex mt-2 space-x-2 item">
                    <textarea name="pekerjaan[]" rows="1" class="w-[90%] border-gray rounded-md" placeholder="Pekerjaan"><?= Html::encode($poin) ?></textarea>
                    <button type="button" class="p-2 text-white bg-red-500 rounded-md remove-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24">
                            <path fill="white" d="M7 21q-.825 0-1.412-.587T5 19V6H4V4h5V3h6v1h5v2h-1v13q0 .825-.587 1.413T17 21zm2-4h2V8H9zm4 0h2V8h-2z" />
                        </svg>
                    </button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <button class="relative z-40 block w-full py-3 mt-5 mb-3 text-white bg-green-500 rounded-md" type="button" id="add-item">Tambah Item</button>



    <div class="h-[80px] w-full"></div>
    <div class="absolute bottom-0 left-0 right-0 ">
        <div class="">
            <?= $this->render('@backend/views/components/element/_submit-button', ['text' => 'Submit']); ?>
        </div>
    </div>
</div>



<?php ActiveForm::end(); ?>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const addItemButton = document.getElementById("add-item");
        const itemsContainer = document.getElementById("items-container");

        // Ensure $poinArray is an array, otherwise set itemCount to 0
        let itemCount = <?= is_array($poinArray) ? count($poinArray) : 0 ?>;

        addItemButton.addEventListener("click", function() {
            itemCount++; // Increment item count

            const newItem = document.createElement("div");
            newItem.classList.add("item", "flex", "space-x-2", "mt-2");

            const input = document.createElement("textarea");
            input.rows = "1";
            input.name = "pekerjaan[]";
            input.classList.add("w-[90%]", "border-gray", "rounded-md");
            input.placeholder = "Pekerjaan";

            const removeButton = document.createElement("button");
            removeButton.type = "button";
            removeButton.classList.add("remove-item", "p-2", "bg-red-500", "text-white", "rounded-md");
            removeButton.innerHTML = "<svg xmlns='http://www.w3.org/2000/svg' width='28' height='28' viewBox='0 0 24 24'><path fill='white' d='M7 21q-.825 0-1.412-.587T5 19V6H4V4h5V3h6v1h5v2h-1v13q0 .825-.587 1.413T17 21zm2-4h2V8H9zm4 0h2V8h-2z'/></svg>";

            removeButton.addEventListener("click", function() {
                newItem.remove(); // Remove the item when the "Remove" button is clicked
                itemCount--; // Decrease item count when an item is removed
            });

            newItem.appendChild(input);
            newItem.appendChild(removeButton);
            itemsContainer.appendChild(newItem);
        });

        // Handle item removal for already existing items
        itemsContainer.addEventListener("click", function(event) {
            if (event.target.classList.contains("remove-item")) {
                event.target.parentNode.remove(); // Remove the item from the container
                itemCount--; // Decrease item count when an item is removed
            }
        });

        // If there are no items initially, add one empty item (ensures there's at least one input field)
        if (itemCount === 0) {
            const initialItem = document.createElement("div");
            initialItem.classList.add("item", "flex", "space-x-2", "mt-2");

            const input = document.createElement("textarea");
            input.rows = "1";
            input.name = "pekerjaan[]";
            input.classList.add("w-[90%]", "border-gray", "rounded-md");
            input.placeholder = "Pekerjaan";

            const removeButton = document.createElement("button");
            removeButton.type = "button";
            removeButton.classList.add("remove-item", "p-2", "bg-red-500", "text-white", "rounded-md");
            removeButton.innerHTML = "<svg xmlns='http://www.w3.org/2000/svg' width='28' height='28' viewBox='0 0 24 24'><path fill='white' d='M7 21q-.825 0-1.412-.587T5 19V6H4V4h5V3h6v1h5v2h-1v13q0 .825-.587 1.413T17 21zm2-4h2V8H9zm4 0h2V8h-2z'/></svg>";

            removeButton.addEventListener("click", function() {
                initialItem.remove(); // Remove the item when the "Remove" button is clicked
                itemCount--; // Decrease item count
            });

            initialItem.appendChild(input);
            initialItem.appendChild(removeButton);
            itemsContainer.appendChild(initialItem);
        }
    });
</script>