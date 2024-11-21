<?php

use backend\models\helpers\JamKerjaHelper;
use backend\models\helpers\PeriodeGajiHelper;
use backend\models\Tanggal;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\TransaksiGaji $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="transaksi-gaji-form">

    <div class="row gap-2  table-container ">
        <div class="col-md-5 col-12">
            <div class="col-12">
                <?= DetailView::widget(
                    [
                        'model' => $rekapandata,
                        'template' => '<tr><th>{label}</th><td>{value}</td></tr>',
                        'attributes' => [
                            [
                                'label' => 'Nama',
                                'value' => function ($model) {
                                    return   $model['karyawan']['nama'];
                                }
                            ],
                            [
                                'label' => 'Kode Karyawan',
                                'value' => function ($model) {
                                    return   $model['karyawan']['kode_karyawan'];
                                }
                            ],
                            [
                                'label' => 'Nomor Identitas',
                                'value' => function ($model) {
                                    return   $model['karyawan']['nomer_identitas'];
                                }
                            ],
                            [
                                'label' => 'Bagian',
                                'value' => function ($model) {
                                    return   $model['dataPekerjaan']['bagian']['nama_bagian'];
                                }
                            ],
                            [
                                'label' => 'Jabatan',
                                'value' => function ($model) {
                                    return   $model['dataPekerjaan']['jabatan']['nama_kode'];
                                }
                            ],
                            [
                                'label' => 'Status Karyawan',
                                'value' => function ($model) {
                                    return   $model['dataPekerjaan']['statusKaryawan']['nama_kode'];
                                }
                            ],
                            [
                                'label' => 'Hari Kerja',
                                'value' => function ($model) {
                                    $data = JamKerjaHelper::getJamKerja($model['karyawan']['id_jam_kerja']);
                                    return   $data['nama_jam_kerja'];
                                }
                            ],
                            [
                                'label' => "Periode Gaji",
                                'format' => 'raw',
                                'value' => function ($model) {
                                    $data =  PeriodeGajiHelper::getPeriodeGaji($model['periode_gaji']);
                                    $tanggal = new Tanggal();
                                    $result = "<div class> 
                                    <p class='m-0'>{$tanggal->getBulan($data['bulan'])} {$data['tahun']} </p>
                                    <span class='text-xs'>({$tanggal->getIndonesiaFormatTanggal($data['tanggal_awal'])} - <br/> {$tanggal->getIndonesiaFormatTanggal($data['tanggal_akhir'])})    
                                    </div>";

                                    return $result;
                                }
                            ],
                            [
                                'label' => "Jumlah Hari kerja",
                                'value' => function ($model) {
                                    $data = $model['karyawan']['total_hari_kerja'];
                                    return   $data . ' Hari';
                                }
                            ],

                            [
                                'label' => "Jumlah Hadir",
                                'value' => function ($model) {
                                    $data = $model['absensiData']['total_hadir'] ?? 0;
                                    return   $data . ' Hari';
                                }
                            ],
                            [
                                'label' => "Jumlah Sakit",
                                'value' => function ($model) {
                                    $data =  $model['absensiData']['total_sakit'] ?? 0;
                                    return   $data . ' Hari';
                                }
                            ],

                            [
                                'label' => "Jumlah Cuti",
                                'value' => function ($model) {
                                    $data = $model['totalCuti'] ?? 0;
                                    return   $data . ' Hari';
                                }
                            ],

                        ],
                    ]
                ) ?>
                 <div class="row">
                <div class="col-12">
                    <?= DetailView::widget(
                        [
                            'model' => $rekapandata,
                            'template' => '<tr><th>{label}</th><td>{value}</td></tr>',
                            'attributes' => [

                                [
                                    'label' => "Gaji Pokok",
                                    'value' => function ($model) {
                                        return  'Rp ' . number_format($model['gajiPokok']['nominal_gaji'], 0, ',', '.');
                                    }
                                ],

                            ],
                        ]
                    ) ?>
                </div>
                <div class="col-12">
                    <?= DetailView::widget(
                        [
                            'model' => $rekapandata,
                            'template' => '<tr><th>{label}</th><td>{value}</td></tr>',
                            'attributes' => [


                                [
                                    'label' => "Jumlah Tunjangan",
                                    'value' => function ($model) {
                                        // dd($model);
                                        return 'Rp ' . number_format($model['getTunjangan'], 0, ',', '.') . ' ' .
                                            Html::a('<i class="fa fa-edit"></i>', ['tunjangan-detail/index', 'id_karyawan' => $model['karyawan']['id_karyawan']], ['class' => 'edit-button d-inline-block', 'target' => '_blank']);
                                    },
                                    'format' => 'raw', // Menambahkan format raw agar HTML ditampilkan
                                ],

                            ],
                        ]
                    ) ?>
                </div>
                <div class="col-12">
                    <?= DetailView::widget(
                        [
                            'model' => $rekapandata,
                            'template' => '<tr><th>{label}</th><td>{value}</td></tr>',
                            'attributes' => [
                                [
                                    'label' => "Jumlah Potongan",
                                    'value' => function ($model) {
                                        return 'Rp ' . number_format($model['getPotongan'], 0, ',', '.') . ' ' .
                                            Html::a('<i class="fa fa-edit"></i>', ['potongan-detail/index', 'id_karyawan' => $model['karyawan']['id_karyawan']], ['class' => 'edit-button d-inline-block', 'target' => '_blank']);
                                    },
                                    'format' => 'raw',
                                ],
                            ],
                        ]
                    ) ?>
                </div>

            </div>

            </div>
        </div>
        <div class="col-12 col-md-7">

           

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'kode_karyawan')->hiddenInput(['maxlength' => true, 'value' => $rekapandata['karyawan']['kode_karyawan'] ?? $model->kode_karyawan])->label(false) ?>
            <?= $form->field($model, 'nomer_identitas')->hiddenInput(['maxlength' => true, 'value' => $rekapandata['karyawan']['nomer_identitas'] ?? $model->nomer_identitas])->label(false) ?>
            <?= $form->field($model, 'nama')->hiddenInput(['maxlength' => true, 'value' => $rekapandata['karyawan']['nama'] ?? $model->nama])->label(false) ?>
            <?= $form->field($model, 'bagian')->hiddenInput(['maxlength' => true, 'value' => $rekapandata['dataPekerjaan']['bagian']['nama_bagian'] ?? $model->bagian])->label(false) ?>
            <?= $form->field($model, 'jabatan')->hiddenInput(['maxlength' => true, 'value' => $rekapandata['dataPekerjaan']['jabatan']['nama_kode'] ?? $model->jabatan])->label(false) ?>
            <?= $form->field($model, 'jam_kerja')->hiddenInput(['value' => $rekapandata['karyawan']['id_jam_kerja'] ?? $model->jam_kerja])->label(false) ?>
            <?= $form->field($model, 'status_karyawan')->hiddenInput(['maxlength' => true, 'value' => $rekapandata['dataPekerjaan']['statusKaryawan']['nama_kode'] ?? $model->status_karyawan])->label(false) ?>
            <?= $form->field($model, 'periode_gaji')->hiddenInput(['maxlength' => true, 'value' => $rekapandata['periode_gaji']['id_periode_gaji']])->label(false) ?>
            <?= $form->field($model, 'jumlah_hari_kerja')->hiddenInput(['value' => $rekapandata['karyawan']['total_hari_kerja'] ?? $model->jumlah_hari_kerja])->label(false) ?>
            <?= $form->field($model, 'jumlah_hadir')->hiddenInput(['value' => $rekapandata['absensiData']['total_hadir'] ?? $model->jumlah_hadir])->label(false) ?>
            <?= $form->field($model, 'jumlah_sakit')->hiddenInput(['value' => $rekapandata['absensiData']['total_sakit'] ?? $model->jumlah_sakit])->label(false) ?>
            <?= $form->field($model, 'jumlah_cuti')->hiddenInput(['value' => $rekapandata['totalCuti'] ?? $model->jumlah_cuti])->label(false) ?>
            <?= $form->field($model, 'gaji_pokok')->hiddenInput(['maxlength' => true, 'value' => $rekapandata['gajiPokok']['nominal_gaji'] ?? $model->gaji_pokok])->label(false) ?>

            <?php
            // dd($rekapandata['getTunjangan']);
            $data = strval($rekapandata['getTunjangan']);
            echo $form->field($model, 'jumlah_tunjangan')->hiddenInput(['maxlength' => true, 'id' => 'tunjangan', 'value' => $data, 'readonly' => true])->label(false);
            ?>
            <?php
            $dataPotongan = strval($rekapandata['getPotongan']);
            echo $form->field($model, 'jumlah_potongan')->hiddenInput(['maxlength' => true, 'id' => 'potongan', 'value' => $dataPotongan, 'readonly' => true])->label(false);

            $dataJamLembur = $model->isNewRecord ? $rekapandata['jumlahJamLembur']['total_menit'] / 60 : $model->jumlah_jam_lembur;
            echo $form->field($model, 'jumlah_jam_lembur')->hiddenInput(['maxlength' => true, 'value' => $dataJamLembur])->label(false);
            ?>



<div class="row">
            <div class="col-12 col-md-6">
                <?php
                $keteranganTunjanganLainnya = $model->isNewRecord ? '' :  $model->keterangan_tunjangan_lainnya;
                echo $form->field($model, 'keterangan_tunjangan_lainnya')->textInput(['maxlength' => true,  'type' => 'text', 'value' => $keteranganTunjanganLainnya, ])->label("Keterangan Tunjangan Lainnya ");
                ?>
            </div>
            <div class="col-12 col-md-6">
                <?php
                $tunjanganLainnya = $model->isNewRecord ? 0 : (int) $model->tunjangan_lainnya;
                echo $form->field($model, 'tunjangan_lainnya')->textInput(['maxlength' => true, 'type' => 'number', 'value' => $tunjanganLainnya, 'id' => "tunjangan_lainnya"])->label("Jumlah Tunjangan Lainnya <span class='text-danger'>(Rp)</span>");
                ?>
            </div>



            <div class="col-12 col-md-6">
                <?php
                $keteranganPotonganLainnya = $model->isNewRecord ? '' : $model->keterangan_potongan_lainnya;
                echo $form->field($model, 'keterangan_potongan_lainnya')->textInput(['maxlength' => true,  'type' => 'text', 'value' => $keteranganPotonganLainnya, ])->label("Keterangan Potongan Lainnya ");
                ?>
            </div>
            <div class="col-12 col-md-6">
                <?php
                $potongan_lainnya = $model->isNewRecord ? 0 : (int) $model->potongan_lainnya;
                echo $form->field($model, 'potongan_lainnya')->textInput(['maxlength' => true, 'type' => 'number', 'value' => $potongan_lainnya, 'id' => "potongan_lainnya"])->label("Jumlah Potongan Lainnya <span class='text-danger'>(Rp)</span>");
                ?>
            </div>



                <div class="col-12 col-md-4">
                    <?php
                    $dataJamLembur;
                    // Menghitung jumlah jam dan menit
                    if ($model->isNewRecord) {
                        $totalMenit = $rekapandata['jumlahJamLembur']['total_menit']; // Ambil total menit
                        $jam = floor($totalMenit / 60);  // Hitung jam
                        $menit = $totalMenit % 60;       // Hitung sisa menit
                        $dataJamLembur = sprintf('%02d:%02d', $jam, $menit);
                    } else {
                        $dataJamLembur = $model->jumlah_jam_lembur; // Menghitung menit jika ada sisa
                    }

                    // Format waktu menjadi HH:mm

                    // Menampilkan field input dengan nilai yang sudah diformat
                    echo $form->field($model, 'jumlah_jam_lembur')->textInput([
                        'readonly' => true,
                        'maxlength' => true,
                        'value' => $dataJamLembur
                    ])->label("Jumlah Jam Lembur <br/><span class='text-danger'>(Jam : Menit)</span>");
                    ?>
                </div>
                <div class="col-12 col-md-4">
                    <?php
                    $dataLemburPerJam = $model->isNewRecord ? 0 : (int) $model->lembur_perjam;
                    echo $form->field($model, 'lembur_perjam')->textInput(['maxlength' => true,  'type' => 'number', 'value' => $dataLemburPerJam, 'id' => "lembur_perjam"])->label("Bayaran Lembur <br/><span class='text-danger'>(Rp / Jam)</span>");
                    ?>
                </div>
                <div class="col-12 col-md-4">
                    <?php
                    $dataTotalLembur = $model->isNewRecord ? 0 : (int) $model->total_lembur;
                    echo $form->field($model, 'total_lembur')->textInput(['maxlength' => true, 'type' => 'number', 'value' => $dataTotalLembur, 'readonly' => true, 'id' => "total_lembur"])->label("Bayaran Lembur Diterima <span class='text-danger'>(Rp)</span>");
                    ?>
                </div>


                <div class="col-12 col-md-4">
                    <?= $form->field($model, 'jumlah_wfh')->textInput(['readonly' => true, 'type' => 'number', 'value' => $rekapandata['absensiData']['total_wfh'] ?? $model->jumlah_wfh])->label("Jumlah WFH <br/><span class='text-danger'>(Hari)</span>"); ?>
                </div>
                <div class="col-12 col-md-4">
                    <?php
                    $dataPotonganWfhHari = $model->isNewRecord ? 0 : (int) $model->potongan_wfh_hari;
                    echo $form->field($model, 'potongan_wfh_hari')->textInput(['maxlength' => true, 'type' => 'number', 'value' => $dataPotonganWfhHari, 'id' => "potongan_wfh"])->label("Potongan WFH <br/> <span class='text-danger'>(Rp / Hari)</span>");
                    ?>
                </div>
                <div class="col-12 col-md-4">
                    <?php
                    $dataJumlahPotonganWfh = $model->isNewRecord ? 0 : (int) $model->jumlah_potongan_wfh;
                    echo $form->field($model, 'jumlah_potongan_wfh')->textInput(['maxlength' => true, 'readonly' => true, 'value' => $dataJumlahPotonganWfh, 'id' => "jumlah_potongan_wfh"])->label("Jumlah Potongan WFH <span class='text-danger'>(Rp)</span>");
                    ?>
                </div>

                <div class="col-12 col-md-4">
                    <?php
                    $dataTotalHariKerja = $rekapandata['karyawan']['total_hari_kerja'];
                    $dataTotalHadir = $rekapandata['absensiData']['total_hadir'] ?? 0;
                    $dataSakit =  $rekapandata['absensiData']['total_sakit'] ?? 0;
                    $dataCuti = $rekapandata['totalCuti'] ?? 0;
                    $total =    $dataTotalHariKerja - $dataTotalHadir - $dataSakit - $dataCuti;

                    $dataJUmlahTIdakHadir = $model->isNewRecord ? $total : $model->jumlah_tidak_hadir;
                    echo $form->field($model, 'jumlah_tidak_hadir')->textInput(['maxlength' => true, 'readonly' => true, 'value' => $dataJUmlahTIdakHadir, 'id' => "jumlah_tidak_hadir"])->label("Jumlah Tidak Hadir <span class='text-danger'><br/>(Hari)</span>");
                    ?>
                </div>
                <div class="col-12 col-md-4">
                    <?php
                    $data_potongan_tidak_hadir_hari = $model->isNewRecord ? 0 : (int)  $model->potongan_tidak_hadir_hari;
                    echo $form->field($model, 'potongan_tidak_hadir_hari')->textInput(['maxlength' => true, 'type' => 'number',  'value' => $data_potongan_tidak_hadir_hari, 'id' => "potongan_tidak_hadir_hari"])->label("Potongan Tidak Hadir <br/><span class='text-danger'>(Rp / Hari)</span>");
                    ?>
                </div>
                <div class="col-12 col-md-4">
                    <?php
                    $data_jumlah_potongan_tidak_hadir = $model->isNewRecord ? 0 : (int) $model->jumlah_potongan_tidak_hadir;
                    echo $form->field($model, 'jumlah_potongan_tidak_hadir')->textInput(['maxlength' => true, 'type' => 'number',  'readonly' => true,  'value' => $data_jumlah_potongan_tidak_hadir, 'id' => "jumlah_potongan_tidak_hadir"])->label("Total Potongan Tidak Hadir <span class='text-danger'>(Rp)</span>");
                    ?>
                </div>


                <div class="col-12 col-md-4">
                    <?php
                    $data_jumlah_terlambat = $model->isNewRecord ? ($rekapandata['getTerlambat'] ?? '00:00') : $model->jumlah_terlambat;
                    echo $form->field($model, 'jumlah_terlambat')->textInput(['maxlength' => true, 'type' => 'time', 'readonly' => 'true',   'value' => $data_jumlah_terlambat, 'id' => "jumlah_terlambat"])->label("Total Terlambat <br/><span class='text-danger'>(Jam : Menit)</span>");
                    ?>
                </div>

                <div class="col-12 col-md-4">
                    <?php
                    $data_potongan_terlambat_permenit = $model->isNewRecord ? 0 : (int) $model->potongan_terlambat_permenit;
                    echo $form->field($model, 'potongan_terlambat_permenit')->textInput(['maxlength' => true, 'type' => 'number',  'value' => $data_potongan_terlambat_permenit, 'id' => "potongan_terlambat_permenit"])->label("Potongan Terlambat <br/><span class='text-danger'>(Rp / Menit)</span>");
                    ?>
                </div>
                <div class="col-12 col-md-4">
                    <?php
                    $data_jumlah_potongan_terlambat = $model->isNewRecord ? 0 : (int) $model->jumlah_potongan_terlambat;
                    echo $form->field($model, 'jumlah_potongan_terlambat')->textInput(['maxlength' => true, 'type' => 'number', 'readonly' => 'true',   'value' => $data_jumlah_potongan_terlambat, 'id' => "jumlah_potongan_terlambat"])->label("Jumlah Potongan Terlambat <span class='text-danger'>(Rp)</span>");
                    ?>
                </div>

            </div>




            <?php
            $data = $model->isNewRecord ? 0 : (int) $model->gaji_diterima;
            echo $form->field($model, 'gaji_diterima')

                ->textInput(['id' => "gaji_diterima", 'maxlength' => true, 'readonly' => true, 'value' => $data])
                ->label("Gaji Diterima Karyawan <span class='text-danger'>(Rp)</span>");
            echo "<p class='text-muted text-xs mt-0 text-capitalize'>Gaji Yang diterima sudah termasuk kalkulasi tunjangan,  potongan karyawan, WFH, lembur,  serta potongan terlambat dan tidak hadir</p>";

            ?>


            <div class="form-group">
                <button class="add-button" type="submit">
                    <span>
                        Save
                    </span>
                </button>
            </div>


            <?php ActiveForm::end(); ?>.
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <?php
    if ($model->isNewRecord) {
        $dataToday = ArrayHelper::toArray($rekapandata);
        $isNewRecord = json_decode(1);
        $rekap = json_encode($dataToday, JSON_PRETTY_PRINT);
    } else {
        $isNewRecord = json_decode(0);
        $dataToday = ArrayHelper::toArray($model);
        $rekap = json_encode($dataToday, JSON_PRETTY_PRINT);
    }
    ?>
    <script>
        let todayJson = <?= $rekap ?>;
        let isnewrecord = <?= $isNewRecord  ?> ? true : false;

        let gaji_pokok, potonganWFH, tambahanLembur, jumlahTunjangan, jumlahPotongan, jumlahTidakHadir, jumlahAlfa, jumlahPotonganTerlambat, waktuTerlambat , tunjangan_lainnya , potongan_lainnya;


        function convertToMinutes(timeString) {
            const [hours, minutes, seconds] = timeString.split(':').map(Number);

            const totalMinutes = hours * 60 + minutes + seconds / 60;
            return totalMinutes;
        }

        function gajiDiterima() {
            console.info(Number(gaji_pokok), Number(tambahanLembur), Number(jumlahTunjangan), Number(jumlahPotongan), Number(potonganWFH), Number(jumlahAlfa), Number(jumlahPotonganTerlambat));
            gaji_diterima = Number(gaji_pokok) + Number(tambahanLembur) + Number(jumlahTunjangan) - Number(jumlahPotongan) - Number(potonganWFH) - Number(jumlahAlfa) - Number(jumlahPotonganTerlambat) - Number(potongan_lainnya) + Number(tunjangan_lainnya);
            $('#gaji_diterima').val(Math.floor(gaji_diterima));
        }



        if (isnewrecord) {
            // Jika isnewrecord benar
            gaji_pokok = todayJson?.gajiPokok?.nominal_gaji;
            potonganWFH = 0;
            tambahanLembur = 0;
            jumlahTunjangan = $('#tunjangan').val();
            jumlahPotongan = $('#potongan').val();
            jumlahTidakHadir = $("#jumlah_tidak_hadir").val();
            jumlahAlfa = 0;
            jumlahPotonganTerlambat = 0;
            jumlahWFH = Number(todayJson?.absensiData?.total_wfh);
            waktuTerlambat = convertToMinutes(todayJson?.getTerlambat);
            tunjangan_lainnya = 0;
            potongan_lainnya = 0
        } else {
            // Jika isnewrecord salah
            console.info(todayJson);
            gaji_pokok = todayJson?.gaji_pokok;
            potonganWFH = $('#jumlah_potongan_wfh').val();
            tambahanLembur = $('#total_lembur').val(); // Pastikan mengakses .val() jika ingin mengambil nilai
            jumlahTunjangan = $('#tunjangan').val();
            jumlahPotongan = $('#potongan').val();
            jumlahTidakHadir = $('#jumlah_tidak_hadir').val();;
            jumlahAlfa = $("#jumlah_potongan_tidak_hadir").val();
            jumlahPotonganTerlambat = $("#jumlah_potongan_terlambat").val();
            jumlahWFH = todayJson.jumlah_wfh;
            waktuTerlambat = convertToMinutes($('#jumlah_terlambat').val());
            tunjangan_lainnya = $("#tunjangan_lainnya").val(); 
            potongan_lainnya = $("#potongan_lainnya").val();
        }


        $('#potongan_lainnya').keyup(function(e) {
            e.preventDefault();
            potongan_lainnya = Number(this.value); 
            gajiDiterima();

        });
        $('#tunjangan_lainnya').keyup(function(e) {
            e.preventDefault();
            tunjangan_lainnya = Number(this.value); 
            gajiDiterima();

        });



        let totalLembur = 0;
        let lemburPerjam = isnewrecord ? todayJson?.jumlahJamLembur.total_menit / 60 : convertToMinutes(todayJson?.jumlah_terlambat);

        $('#lembur_perjam').keyup(function(e) {
            e.preventDefault();
            tambahanLembur = Number(this.value) * lemburPerjam;
            $('#total_lembur').val(Number(this.value) * lemburPerjam)
            gajiDiterima();

        });

        // potongan wfh
        $('#potongan_wfh').keyup(function(e) {
            e.preventDefault();
            potonganWFH = Number(this.value) * jumlahWFH;
            $('#jumlah_potongan_wfh').val(Number(this.value) * jumlahWFH);
            gajiDiterima();
        })
        //memnentukan pembayaran lembur



        $("#potongan_tidak_hadir_hari").keyup(function(e) {
            e.preventDefault();
            console.info(this.value)
            jumlahAlfa = Number(this.value) * jumlahTidakHadir;
            $('#jumlah_potongan_tidak_hadir').val(Number(this.value) * jumlahTidakHadir)
            gajiDiterima();
        });



        $("#potongan_terlambat_permenit").keyup(function(e) {
            e.preventDefault();

            jumlahPotonganTerlambat = Number(this.value) * waktuTerlambat;
            $('#jumlah_potongan_terlambat').val(Math.floor(Number(this.value) * waktuTerlambat));

            gajiDiterima();
        });
        gajiDiterima();
    </script>

</div>