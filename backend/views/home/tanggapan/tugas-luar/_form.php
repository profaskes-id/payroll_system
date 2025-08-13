<?php
use backend\models\helpers\KaryawanHelper;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanTugasLuar $model */
/** @var backend\models\DetailTugasLuar[] $detailModels */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="container p-6 mx-auto">
    <div class="p-6 bg-white rounded-lg shadow-md">
        <?php $form = ActiveForm::begin(['options' => ['class' => 'space-y-4']]); ?>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="">
            <p class="block text-sm font-medium text-gray-900 capitalize ">Karyawan</p>
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

            <div>
                <?= $form->field($model, 'tanggal')->input('date', [
                    'class' => 'block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500'
                ])->label('Diajukan Untuk Tanggal', ['class' => 'block text-sm font-medium text-gray-700']) ?>
            </div>
        </div>

        <?php if (!$model->isNewRecord): ?>
            <div class="grid grid-cols-1 gap-4 mt-4 md:grid-cols-2">
                <div>
                    <?= $form->field($model, 'status_pengajuan')->radioList(
                        [
                            0 => 'Pending',
                            1 => 'Approve'
                        ],
                        [
                            'item' => function ($index, $label, $name, $checked, $value) {
                                $id = 'status_pengajuan_' . $index;
                                $checked = $checked ? 'checked' : '';
                                $color = $value == 1 ? 'text-green-600' : 'text-yellow-600';

                                return "
                                <div class='flex items-center space-x-2'>
                                    <input class='h-4 w-4 border-gray-300 $color focus:ring-$color' type='radio' name='{$name}' id='{$id}' value='{$value}' {$checked}>
                                    <label class='text-sm font-medium text-gray-700' for='{$id}'>
                                        {$label}
                                    </label>
                                </div>
                            ";
                            }
                        ]
                    )->label('Status Pengajuan', ['class' => 'block text-sm font-medium text-gray-700']) ?>
                </div>

                <div>
                    <?= $form->field($model, 'catatan_approver')->textarea([
                        'rows' => 2,
                        'placeholder' => 'Masukkan catatan persetujuan...',
                        'class' => 'block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500'
                    ])->label('Catatan Approver', ['class' => 'block text-sm font-medium text-gray-700']) ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Detail Tugas Luar Section -->
        <div class="mt-6">
            <h5 class="pb-2 text-lg font-medium text-gray-900 border-b border-gray-200">Detail Tugas Luar</h5>
            <div id="detail-container" class="mt-3 space-y-3">
                <?php if (!empty($detailModels)): ?>
                    <?php foreach ($detailModels as $index => $detailModel): ?>
                        <div class="p-4 bg-white rounded-lg shadow detail-item" data-index="<?= $index ?>">
                            <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                                <div>
                                    <?= $form->field($detailModel, "[$index]keterangan", [
                                        'options' => ['class' => 'mb-0']
                                    ])->textInput([
                                        'placeholder' => 'Contoh: Ke Jakarta untuk meeting',
                                        'class' => 'block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500'
                                    ])->label(false) ?>
                                </div>
                                <div>
                                    <?= $form->field($detailModel, "[$index]jam_diajukan", [
                                        'options' => ['class' => 'mb-0']
                                    ])->input('time', [
                                        'class' => 'block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500'
                                    ])->label(false) ?>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-3 mt-3 md:grid-cols-2">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Status Detail</label>
                                    <div class="flex mt-1 space-x-4">
                                        <?= Html::radioList(
                                            "DetailTugasLuar[$index][status_pengajuan_detail]",
                                            $detailModel->status_pengajuan_detail ?? 1,
                                            [
                                                1 => 'Disetujui',
                                                2 => 'Ditolak'
                                            ],
                                            [
                                                'item' => function ($index, $label, $name, $checked, $value) {
                                                    $id = 'status_pengajuan_detail_' . $index . '_' . $value;
                                                    $checked = $checked ? 'checked' : '';
                                                    $color = $value == 1 ? 'text-green-600' : 'text-red-600';
                                                    
                                                    return "
                                                    <div class='flex items-center space-x-2'>
                                                        <input class='h-4 w-4 border-gray-300 $color focus:ring-$color' type='radio' name='{$name}' id='{$id}' value='{$value}' {$checked}>
                                                        <label class='text-sm font-medium text-gray-700' for='{$id}'>
                                                            {$label}
                                                        </label>
                                                    </div>";
                                                }
                                            ]
                                        ) ?>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end mt-3">
                                <button type="button" class="remove-detail inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" data-index="<?= $index ?>">
                                    <svg class="-ml-0.5 mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    Hapus
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <button type="button" id="add-detail" class="mt-3 inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="-ml-0.5 mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Detail
            </button>
        </div>

        <div class="mt-8">
            <button type="submit" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Simpan
            </button>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"> </script>

<script>
    $(document).ready(function() {
        // Add new detail item
        $('#add-detail').click(function() {
            var index = $('.detail-item').length;
            var uniqueId = Date.now() + index; // Membuat ID unik
            
            var html = `
                <div class="p-4 bg-white rounded-lg shadow detail-item" data-index="\${index}">
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        <div>
                            <input type="text" id="detailtugasluar-\${uniqueId}-keterangan" 
                                class="block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                name="DetailTugasLuar[\${index}][keterangan]" 
                                placeholder="Contoh: Ke Jakarta untuk meeting">
                        </div>
                        <div>
                            <input type="time" id="detailtugasluar-\${uniqueId}-jam_diajukan" 
                                class="block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                name="DetailTugasLuar[\${index}][jam_diajukan]">
                        </div>
                    </div>
    
                    <div class="grid grid-cols-1 gap-3 mt-3 md:grid-cols-2">
                        <div>
                            <label class="block text-xs font-medium text-gray-500">Status Detail</label>
                            <div class="flex mt-1 space-x-4">
                                <div class="flex items-center space-x-2">
                                    <input class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-600" type="radio" 
                                        name="DetailTugasLuar[\${index}][status_pengajuan_detail]" 
                                        id="detailtugasluar-\${uniqueId}-status_pengajuan_detail1" 
                                        value="1" checked>
                                    <label class="text-sm font-medium text-gray-700" for="detailtugasluar-\${uniqueId}-status_pengajuan_detail1">Disetujui</label>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <input class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-600" type="radio" 
                                        name="DetailTugasLuar[\${index}][status_pengajuan_detail]" 
                                        id="detailtugasluar-\${uniqueId}-status_pengajuan_detail2" 
                                        value="2">
                                    <label class="text-sm font-medium text-gray-700" for="detailtugasluar-\${uniqueId}-status_pengajuan_detail2">Ditolak</label>
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <div class="flex justify-end mt-3">
                        <button type="button" class="remove-detail inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" data-index="\${index}">
                            <svg class="-ml-0.5 mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            Hapus
                        </button>
                    </div>
                </div>
            `;
            $('#detail-container').append(html);
        });
    


        // Remove detail item
        $(document).on('click', '.remove-detail', function(e) {
            e.preventDefault();
            console.info(e)
            if (confirm('Apakah Anda yakin ingin menghapus detail ini?')) {
                var index = $(this).data('index');
                var item = $('.detail-item[data-index="' + index + '"]');
                
                // Jika item sudah ada di database (ada input hidden _id), kita perlu menandainya untuk dihapus
                var idInput = item.find('input[name*="[id]"]');
                if (idInput.length > 0) {
                    // Buat input hidden untuk menandai penghapusan
                    if (item.find('input[name*="[toDelete]"]').length === 0) {
                        item.append('<input type="hidden" name="DetailTugasLuar[' + index + '][toDelete]" value="1">');
                    }
                    item.hide();
                } else {
                    // Jika baru dibuat (belum ada di database), hapus langsung
                    item.remove();
                }
                
                // Reindex remaining items
                reindexDetailItems();
            }
        });
        
        // Function to reindex all detail items
        function reindexDetailItems() {
            var newIndex = 0;
            $('.detail-item:visible').each(function() {
                var oldIndex = $(this).data('index');
                $(this).attr('data-index', newIndex);
                
                // Update all input names to use new index
                $(this).find('[name*="DetailTugasLuar"]').each(function() {
                    var name = $(this).attr('name').replace(/DetailTugasLuar\[(\d+)\]/, 'DetailTugasLuar[' + newIndex + ']');
                    $(this).attr('name', name);
                    
                    // Update ID jika ada
                    var id = $(this).attr('id');
                    if (id) {
                        $(this).attr('id', id.replace(/DetailTugasLuar(\d+)/, 'DetailTugasLuar' + newIndex));
                    }
                });
                
                // Update labels for attributes
                $(this).find('label').each(function() {
                    var forAttr = $(this).attr('for');
                    if (forAttr) {
                        $(this).attr('for', forAttr.replace(/DetailTugasLuar(\d+)/, 'DetailTugasLuar' + newIndex));
                    }
                });
                
                // Update the remove button's data-index
                $(this).find('.remove-detail').data('index', newIndex);
                
                newIndex++;
            });
        }
    });
</script>
