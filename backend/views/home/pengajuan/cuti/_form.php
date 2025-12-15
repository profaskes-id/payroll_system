<?php


use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;

$form = ActiveForm::begin(); ?>

<div class="relative ">


    <p class="-mb-3 text-base capitalize">click untuk memilih jenis cuti</p>
    <div class="grid grid-cols-2 gap-5 my-4">
        <?php foreach ($jenisCuti as $key => $value) : ?>
            <div>
                <label
                    for="<?= $value->id_master_cuti ?>"
                    class="block cursor-pointer rounded-lg border border-gray-100 bg-white p-4 text-sm font-medium shadow-sm hover:border-gray-200 has-[:checked]:border-blue-500 has-[:checked]:ring-1 has-[:checked]:ring-blue-500">
                    <div>

                        <p class="mt-1 text-gray-900"><?= $value->jenis_cuti ?></p>
                        <p class="mt-1 text-xs text-gray-500">
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
    <p class="mb-5 -mt-1 text-sm text-red-500 capitalize" id="error"></p>



    <div class="mb-5">
        <label for="tanggal" class="block mb-2 text-sm font-medium text-gray-900 capitalize">Tanggal</label>
        <?= $form->field($model, 'tanggal')->textInput([
            'id' => 'tanggal-multi',
            'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5',
        ])->label(false) ?>
        <p class="m-0 -mt-1 text-xs text-gray-700">Anda dapat memilih beberapa tanggal sekaligus.</p>
    </div>

    <p class="hidden mb-5 -mt-1 text-sm text-red-500 capitalize" id="error-year"></p>
    <div class="mb-5">
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 capitalize">Jumlah Hari</label>
        <input type="text" disabled class="disabled:bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" id="jumlah_hari">
        <!-- <p class="text-xs">Jika pengajuan cuti disetujui, hari cuti Anda tidak akan dihitung pada hari non-kerja . Sisa jatah cuti akan tetap terjaga.</p> -->
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


<script>
    // contoh: maksimal tanggal dipilih = 12
    flatpickr("#tanggal-multi", {
        mode: "multiple",
        dateFormat: "Y-m-d",
        minDate: "today",
        maxDate: new Date().fp_incr(365), // 1 tahun ke depan
        onChange: function(selectedDates, dateStr, instance) {
            let sisa = parseInt($('#sisa_hari').val()?.split(' ')[0]) || 0;
            if (selectedDates.length > sisa) {
                selectedDates.pop(); // hapus tanggal terakhir
                instance.setDate(selectedDates); // update kembali
                alert("Jumlah tanggal melebihi jatah cuti");
            }
        }
    });
</script>


<?php
// $jenisCutinew = ArrayHelper::toArray($jenisCuti);
$rekapCutinew = ArrayHelper::toArray($rekapCuti);

// $jenisCutiJson = json_encode($jenisCutinew);
$rekapCutiJson = json_encode($rekapCutinew);
?>

<script>
    $(document).ready(function() {

        let jenisCutiData = [];

        $.ajax({
            type: "get",
            url: '/panel/pengajuan/jenis-cuti',
            // data: "data",
            dataType: "json",
            success: function(response) {
                $.each(response, function(key, value) {
                    jenisCutiData.push(value);
                });
                let rekapCutiData = <?= $rekapCutiJson ?>;
                let JatahSetahun = 0;

                $('.radio-button').change(function() {
                    let selectedId = $(this).val();
                    let selectedData = jenisCutiData.find(function(item) {
                        return item.id_master_cuti == selectedId;
                    });

                    if (selectedData) {
                        //ambil jatah setajin
                        JatahSetahun = selectedData.jatah_hari_cuti - selectedData.total_hari_terpakai;
                        let newData = rekapCutiData.find(function(item) {
                            return item.id_master_cuti == selectedData.id_master_cuti;
                        })
                        let sisaHari = parseInt(JatahSetahun);
                        $('#sisa_hari').val(sisaHari + " Hari");

                    }
                });

                // Perhatikan: ini menggantikan cara hitung jumlah hari untuk flatpickr multiple
                $('#tanggal-multi').change(function() {
                    let selectedDates = $(this).val();

                    if (!selectedDates) {
                        $('#jumlah_hari').val('0 Hari');
                        return;
                    }

                    let datesArray = selectedDates.split(','); // pisahkan string jadi array
                    let jumlahHari = datesArray.length;

                    $('#jumlah_hari').val(jumlahHari + ' Hari');

                    // validasi terhadap sisa cuti
                    let data = $('#sisa_hari').val();
                    let dataSekarang = parseInt(data.split(' ')[0]);

                    if (jumlahHari > dataSekarang) {
                        $('#error').show();
                        $('#error').text('Jatah Cuti Tidak Cukup');
                        $('.add-button').attr('disabled', true);
                    } else {
                        $('#error').hide();
                        $('.add-button').attr('disabled', false);
                    }
                });


            }
        });

    });
</script>