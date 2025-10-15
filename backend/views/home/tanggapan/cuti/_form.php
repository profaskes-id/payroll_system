<?php

use backend\models\helpers\KaryawanHelper;
use backend\models\Karyawan;
use backend\models\MasterCuti;
use backend\models\MasterKode;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanCuti $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="relative p-2 bg-white rounded-lg shadow-md md:p-6">

    <?php $form = ActiveForm::begin(); ?>

    <div class="grid grid-cols-1 md:gap-6 md:grid-cols-2">

        <div class="col-span-2">
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
    </div>

    <!-- Jenis Cuti Field -->
    <div class="mb-4">
        <p class="block mb-2 text-sm font-medium text-gray-900 capitalize ">Jenis Cuti</p>

        <?php
        $data = \yii\helpers\ArrayHelper::map(MasterCuti::find()->all(), 'id_master_cuti', 'jenis_cuti');
        echo $form->field($model, 'jenis_cuti')->dropDownList(
            $data,
            [
                'prompt' => 'Pilih Karyawan ...',
                'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 '
            ]
        )->label(false);
        ?>
    </div>



    <div id="detail-container">

        <?php if (!empty($model->detailCuti)): ?>
            <?php foreach ($model->detailCuti as $index => $detailModel): ?>
                <div class="flex items-center gap-4 mb-4 detail-item" data-index="<?= $index ?>">

                    <!-- Tanggal -->
                    <div class="w-[150px]">
                        <?= $form->field($detailModel, "[$index]tanggal", [
                            'options' => ['class' => 'mb-0'],
                            'template' => "{label}\n{input}"
                        ])->input('date', [
                            'class' => 'block w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500'
                        ])->label('Tanggal', ['class' => 'block text-xs font-medium text-gray-700']) ?>
                    </div>

                    <!-- Status -->
                    <div class="flex items-center gap-2 w-[250px]">
                        <label class="text-xs text-gray-700">Status:</label>
                        <?= Html::activeRadioList($detailModel, "[$index]status", [
                            0 => 'Pending',
                            1 => 'Disetujui',
                            2 => 'Ditolak',
                        ], [
                            'class' => 'flex items-center gap-3',
                            'item' => function ($indexVal, $label, $name, $checked, $value) use ($index) {
                                $id = "status_{$value}_$index";
                                return '
                                <label for="' . $id . '" class="inline-flex items-center space-x-1 text-xs text-gray-700">
                                    <input type="radio" id="' . $id . '" name="' . $name . '" value="' . $value . '" ' . ($checked ? 'checked' : '') . ' class="text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span>' . $label . '</span>
                                </label>';
                            }
                        ]) ?>
                    </div>

                    <!-- Keterangan -->
                    <div class="flex-1">
                        <?= $form->field($detailModel, "[$index]keterangan", [
                            'options' => ['class' => 'mb-0'],
                            'template' => "{label}\n{input}"
                        ])->textInput([
                            'class' => 'block w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500',
                            'placeholder' => 'Keterangan'
                        ])->label('Keterangan', ['class' => 'block text-xs font-medium text-gray-700']) ?>
                    </div>

                    <!-- Tombol Hapus -->
                    <div class="flex-shrink-0">
                        <button type="button" class="px-2 py-1 text-xs font-medium text-white bg-red-600 rounded hover:bg-red-700 remove-detail" data-index="<?= $index ?>">
                            Hapus
                        </button>
                    </div>

                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>





    <!-- Alasan Cuti Field -->
    <div class="col-span-2 mb-4">
        <?= $form->field($model, 'alasan_cuti')->textarea([
            'rows' => 2,
            'placeholder' => 'Alasan Cuti Karyawan',
            'class' => 'w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500'
        ]) ?>
    </div>




    <div class="col-12">

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


        <!-- Catatan Admin Field (hanya jika bukan record baru) -->
        <?php if (!$model->isNewRecord): ?>
            <div class="">
                <?= $form->field($model, 'catatan_admin')->textarea([
                    'rows' => 2,
                    'class' => 'w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500'
                ]) ?>
            </div>
        <?php endif; ?>



        <!-- Submit Button -->
    </div>
    <div class="">
        <button type="submit" class="block px-10 py-2 font-bold text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
            Submit
        </button>
    </div>
</div>


<?php ActiveForm::end(); ?>

</div>


<script src="https://code.jquery.com/jquery-3.7.1.slim.min.js" integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>


<script>
    $('#add-detail').click(function() {
        let index = $('.detail-item').length;
        let html = '<div class="flex-wrap mb-3 detail-item d-flex align-items-center justify-content-between" data-index="' + index + '">' +
            // Tanggal (full width di mobile, 3 kolom di md+)
            '<div class="mb-2 mb-md-0 col-12 col-md-3">' +
            '<label for="detailcuti-' + index + '-tanggal" class="form-label">Tanggal</label>' +
            '<input type="date" id="detailcuti-' + index + '-tanggal" name="DetailCuti[' + index + '][tanggal]" class="form-control form-control-sm" />' +
            '</div>' +

            // Status (full width di mobile, 4 kolom di md+)
            '<div class="mb-2 mb-md-0 col-12 col-md-4">' +
            '<label class="form-label d-block">Status</label>' +
            '<div class="d-flex justify-content-between" style="max-width: 250px;">' +
            '<div>' +
            '<input class="form-check-input me-1" type="radio" name="DetailCuti[' + index + '][status]" id="status_pending_' + index + '" value="0" checked>' +
            '<label class="form-check-label me-2" for="status_pending_' + index + '">Pending</label>' +
            '</div>' +
            '<div>' +
            '<input class="form-check-input me-1" type="radio" name="DetailCuti[' + index + '][status]" id="status_approved_' + index + '" value="1">' +
            '<label class="form-check-label me-2" for="status_approved_' + index + '">Disetujui</label>' +
            '</div>' +
            '<div>' +
            '<input class="form-check-input me-1" type="radio" name="DetailCuti[' + index + '][status]" id="status_rejected_' + index + '" value="2">' +
            '<label class="form-check-label" for="status_rejected_' + index + '">Ditolak</label>' +
            '</div>' +
            '</div>' +
            '</div>' +

            // Keterangan (full width di mobile, 4 kolom di md+)
            '<div class="mb-2 mb-md-0 col-12 col-md-4">' +
            '<label for="detailcuti-' + index + '-keterangan" class="form-label">Keterangan</label>' +
            '<input type="text" id="detailcuti-' + index + '-keterangan" name="DetailCuti[' + index + '][keterangan]" class="form-control form-control-sm" placeholder="Masukkan keterangan" />' +
            '</div>' +

            // Tombol hapus (full width di mobile, auto width di md+)
            '<div class="mt-2 col-12 col-md-auto d-flex align-items-start mt-md-0">' +
            '<button type="button" class="btn btn-danger btn-sm remove-detail" data-index="' + index + '">Hapus</button>' +
            '</div>' +
            '</div>' +
            '<hr class="my-2" />';

        $('#detail-container').append(html);
    });



    $(document).on('click', '.remove-detail', function() {
        if (confirm('Yakin ingin menghapus detail ini?')) {
            let index = $(this).data('index');
            $('.detail-item[data-index="' + index + '"]').remove();

            // Reindex items
            $('.detail-item').each(function(i) {
                $(this).attr('data-index', i);
                $(this).find('input, label').each(function() {
                    let attrFor = $(this).attr('for');
                    let name = $(this).attr('name');
                    if (attrFor) {
                        let newFor = attrFor.replace(/\d+/, i);
                        $(this).attr('for', newFor);
                    }
                    if (name) {
                        let newName = name.replace(/\[\d+\]/, '[' + i + ']');
                        $(this).attr('name', newName);
                    }
                    if ($(this).attr('id')) {
                        let newId = $(this).attr('id').replace(/\d+/, i);
                        $(this).attr('id', newId);
                    }
                });
            });
        }
    });
</script>