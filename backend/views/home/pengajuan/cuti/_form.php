<?php

use backend\models\Karyawan;
use backend\models\RekapCuti;
use Codeception\Lib\Di;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$form = ActiveForm::begin(); ?>

<div class="">

    <div class="my-4 grid grid-cols-2 gap-5">
        <?php foreach ($jenisCuti as $key => $value) : ?>
            <div>
                <label
                    for="<?= $value->id_master_cuti ?>"
                    class="block cursor-pointer rounded-lg border border-gray-100 bg-white p-4 text-sm font-medium shadow-sm hover:border-gray-200 has-[:checked]:border-blue-500 has-[:checked]:ring-1 has-[:checked]:ring-blue-500">
                    <div>

                        <p class="mt-1 text-gray-900"><?= $value->jenis_cuti ?></p>
                        <p class="mt-1 text-gray-500 text-xs">
                            <?php echo $value->deskripsi_singkat ?>
                        </p>
                    </div>

                    <input
                        type="radio"
                        name="jenis_cuti"
                        id="<?= $value->id_master_cuti ?>"
                        value="<?= $value->id_master_cuti ?>"
                        class="sr-only radio-button" />
                </label>
            </div>
        <?php endforeach ?>
    </div>

    <div class="mb-1">
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 capitalize">Sisa Hari Cuti</label>
        <?= $form->field($model, 'sisa_hari')->textInput(['id' => 'sisa_hari', 'type' => 'text', 'disabled' => true, 'class' => 'disabled:bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 '])->label(false) ?>
    </div>
    <p class="text-sm text-red-500 mb-5 -mt-1 capitalize" id="error"></p>
    <div class="mb-5">
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 capitalize">tanggal mulai</label>
        <?= $form->field($model, 'tanggal_mulai')->textInput(['id' => 'tanggal_mulai', 'type' => 'date', 'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 '])->label(false) ?>
    </div>
    <div class="mb-5">
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 capitalize">tanggal selesai</label>
        <?= $form->field($model, 'tanggal_selesai')->textInput(['readonly' => true, 'id' => 'tanggal_selesai', 'type' => 'date', 'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 '])->label(false) ?>
    </div>
    <p class="text-sm text-red-500 mb-5 -mt-1 capitalize hidden" id="error-year"></p>

    <div class="mb-5">
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 capitalize">Jumlah Hari</label>
        <input type="text" disabled class="disabled:bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" id="jumlah_hari">
    </div>
    <div class="mb-5">
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 capitalize">alasan cuti</label>
        <?= $form->field($model, 'alasan_cuti')->textarea(['rows' => '4',  'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 '])->label(false) ?>
    </div>
    <div class="col-span-12">
        <div class="">
            <?= $this->render('@backend/views/components/element/_submit-button', ['text' => 'Submit']); ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>


<?php
$jenisCutinew = ArrayHelper::toArray($jenisCuti);
$rekapCutinew = ArrayHelper::toArray($rekapCuti);

$jenisCutiJson = json_encode($jenisCutinew, JSON_PRETTY_PRINT);
$rekapCutiJson = json_encode($rekapCutinew, JSON_PRETTY_PRINT);

?>

<script>
    $(document).ready(function() {
        let jenisCutiData = <?= $jenisCutiJson ?>;
        let rekapCutiData = <?= $rekapCutiJson ?>;

        let JatahSetahun = 0;
        let sisaHariGlobal = 0;
        $('.radio-button').change(function() {
            let selectedId = $(this).val();
            let selectedData = jenisCutiData.find(function(item) {
                return item.id_master_cuti == selectedId;
            });

            //apakah jenis cuti nya ada
            if (selectedData) {
                //ambil jatah setajin
                JatahSetahun = selectedData.total_hari_pertahun;
                let newData = rekapCutiData.find(function(item) {
                    return item.id_master_cuti == selectedData.id_master_cuti;
                })
                if (!newData) {
                    $('#sisa_hari').val(JatahSetahun + " Hari");
                    return
                }
                let sisaHari = parseInt(JatahSetahun) - parseInt(newData?.total_hari_terpakai);
                $('#sisa_hari').val(sisaHari + " Hari");
                sisaHariGlobal = sisaHari;
            }
        });

        $('#tanggal_mulai').change(function(e) {
            $('#tanggal_selesai').attr('readonly', false);

        });

        $('#tanggal_selesai').change(function(e) {

            let startDate = $('#tanggal_mulai').val();
            let endDate = this.value;
            let yearstart = Number(startDate.split('-')[0]);
            let yearEnd = Number(endDate.split('-')[0]);

            if (yearEnd != yearstart) {
                $('#error-year').html('Tidak Boleh Lebih Dari 1 Tahun');
                $('#error-year').show();
                $('.add-button').attr('disabled', true);
            } else {
                // $('#error-year').html('Tidak Boleh Lebih Dari 1 Tahun');
                $('#error-year').hide();
                $('.add-button').attr('disabled', true);

            }
            // Menghitung selisih hari
            let diffInMs = new Date(endDate) - new Date(startDate);
            let diffInDays = diffInMs / (1000 * 60 * 60 * 24);

            $('#jumlah_hari').val(diffInDays + " Hari");

            if (diffInDays > sisaHariGlobal) {
                $('#error').show();
                $('#error').text('Jatah Cuti Tidak Cukup');
                $('.add-button').attr('disabled', true);
            } else if (diffInDays < 0) {
                $('#error-year').show();
                $('#error-year').text('Tanggal Selesai Lebih Kecil dari Tanggal Mulai');
                $('.add-button').attr('disabled', true);
            } else {
                $('#error-year').hide();
                $('#error').hide();
                $('.add-button').attr('disabled', false);
            }

        });
    });
</script>