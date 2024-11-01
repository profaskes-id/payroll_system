<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\TransaksiGaji $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="transaksi-gaji-form">

    <div class="row gap-2  table-container">
        <div class="col-md-4 border border-end">
            <h1>karyawan</h1>
            <p>Nama : <?= $rekapandata['karyawan']['kode_karyawan'] ?></p>
            <p>Nama : <?= $rekapandata['karyawan']['nama'] ?></p>
            <p>Nomer Identitas : <?= $rekapandata['karyawan']['nomer_identitas'] ?></p>
            <br>

            <h1>pekerjaan</h1>
            <p>Total Hari Kerja : <?= $rekapandata['karyawan']['total_hari_kerja'] ?> Hari bulan sekarang</p>
            <p>Jabatan : <?= $rekapandata['dataPekerjaan']['jabatan']['nama_kode'] ?></p>
            <p>Bagian : <?= $rekapandata['dataPekerjaan']['bagian']['nama_bagian'] ?></p>
            <p>Status Karyawan : <?= $rekapandata['dataPekerjaan']['statusKaryawan']['nama_kode'] ?></p>

            <br>
            <h1>Absensi</h1>
            <p>Total Hadir : <?= $rekapandata['absensiData']['total_hadir'] ?> Hari </p>
            <p>Total Sakit : <?= $rekapandata['absensiData']['total_sakit'] ?> Hari</p>
            <p>Total WFH : <?= $rekapandata['absensiData']['total_wfh'] ?> Hari</p>


            <br>
            <h1>Gaji Pokok</h1>
            <p>Nominal Gaji : Rp<?= $rekapandata['gajiPokok']['nominal_gaji'] ?> </p>

            <br>
            <h1>Lembur</h1>
            <p>Telah Lembur Selama : <?= $rekapandata['jumlahJamLembur']['total_jam_lembur'] ?> </p>

            <br>
            <h1>Cuti</h1>
            <p>Cuti Bulan Ini selama : <?= $rekapandata['totalCuti'] ?> Hari </p>

        </div>
        <div class="col-8">
            <?php $form = ActiveForm::begin(); ?>

            <?php echo  $form->field($model, 'nomer_identitas')->textInput(['maxlength' => true, 'value' => $rekapandata['karyawan']['kode_karyawan']])
            ?>

            <?php echo  $form->field($model, 'nama')->textInput(['maxlength' => true, 'value' => $rekapandata['karyawan']['nama']])
            ?>

            <?php echo  $form->field($model, 'bagian')->textInput(['maxlength' => true, 'value' => $rekapandata['dataPekerjaan']['bagian']['nama_bagian']])
            ?>

            <?php echo  $form->field($model, 'jabatan')->textInput(['maxlength' => true, 'value' => $rekapandata['dataPekerjaan']['jabatan']['nama_kode']])
            ?>

            <?php echo  $form->field($model, 'jam_kerja')->textInput(['value' => $rekapandata['karyawan']['total_hari_kerja']])
            ?>

            <?php echo  $form->field($model, 'status_karyawan')->textInput(['maxlength' => true, 'value' => $rekapandata['dataPekerjaan']['statusKaryawan']['nama_kode']])
            ?>

            <?php echo  $form->field($model, 'periode_gaji_bulan')->textInput()
            ?>

            <?php echo  $form->field($model, 'periode_gaji_tahun')->textInput()
            ?>

            <?php echo  $form->field($model, 'jumlah_hari_kerja')->textInput(['value' => $rekapandata['karyawan']['total_hari_kerja']])
            ?>

            <?php echo  $form->field($model, 'jumlah_hadir')->textInput(['value' => $rekapandata['absensiData']['total_hadir']])
            ?>

            <?php echo  $form->field($model, 'jumlah_sakit')->textInput(['value' => $rekapandata['absensiData']['total_sakit']])
            ?>

            <?php echo  $form->field($model, 'jumlah_wfh')->textInput(['value' => $rekapandata['absensiData']['total_wfh']])
            ?>

            <?php echo  $form->field($model, 'jumlah_cuti')->textInput(['value' => $rekapandata['totalCuti']])
            ?>

            <?php echo  $form->field($model, 'gaji_pokok')->textInput(['maxlength' => true, 'value' => $rekapandata['gajiPokok']['nominal_gaji']])
            ?>

            <?php echo  $form->field($model, 'jumlah_jam_lembur')->textInput(['maxlength' => true, 'value' => $rekapandata['jumlahJamLembur']['total_menit'] / 60])
            ?>

            <?php echo  $form->field($model, 'lembur_perjam')->textInput(['maxlength' => true, 'id' => "lembur_perjam"])
            ?>

            <?php echo  $form->field($model, 'total_lembur')->textInput(['maxlength' => true, 'readonly' => true, 'id' => "total_lembur"]) ?>


            <?= $form->field($model, 'jumlah_tunjangan')->textInput(['maxlength' => true,]) ?>

            <?= $form->field($model, 'jumlah_potongan')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'potongan_wfh_hari')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'jumlah_potongan_wfh')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'gaji_diterima')->textInput(['maxlength' => true]) ?>


            <div class="form-group">
                <button class="add-button" type="submit">
                    <span>
                        Save
                    </span>
                </button>
            </div>


            <?php ActiveForm::end(); ?>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <?php
    $dataToday = ArrayHelper::toArray($rekapandata);
    $rekap = json_encode($dataToday, JSON_PRETTY_PRINT);
    ?>
    <script>
        let todayJson = <?= $rekap ?>;
        //memnentukan pembayaran lembur
        let totalLembur = 0;
        let lemburPerjam = todayJson.jumlahJamLembur.total_menit / 60;
        $('#lembur_perjam').change(function(e) {
            e.preventDefault();
            console.info(lemburPerjam)
            console.info(Number(this.value) * lemburPerjam)
            $('#total_lembur').val(Number(this.value) * lemburPerjam)

        });
        let totalPembayaranLembur = lemburPerjam * todayJson?.lembur_perjam;
    </script>

</div>