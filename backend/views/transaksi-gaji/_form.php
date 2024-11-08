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

    <div class="row gap-2  table-container">
        <div class="col-md-5 ">
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
                                'label' => "Jumlah WFH",
                                'value' => function ($model) {
                                    $data = $model['absensiData']['total_wfh'] ?? 0;
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
                            [
                                'label' => "Tidak Hadir",
                                'value' => function ($model) {
                                    $dataTotalHariKerja = $model['karyawan']['total_hari_kerja'];
                                    $dataTotalHadir = $model['absensiData']['total_hadir'] ?? 0;
                                    $dataSakit =  $model['absensiData']['total_sakit'] ?? 0;
                                    $dataCuti = $model['totalCuti'] ?? 0;
                                    return   $dataTotalHariKerja - $dataTotalHadir - $dataSakit - $dataCuti . ' Hari';
                                }
                            ],
                            [
                                'label' => "Jumlah Jam Lembur",
                                'value' => function ($model) {
                                    $totalMenit = $model['jumlahJamLembur']['total_menit']; // 490 menit
                                    $jam = floor($totalMenit / 60); // Jam = total menit dibagi 60
                                    $menit = $totalMenit % 60; // Menit = sisa dari total menit setelah dibagi 60
                                    $detik = 0; // Anda bisa menetapkan detik ke 0, karena hanya dalam menit
                                    $formattedTime = sprintf('%02d:%02d:%02d', $jam, $menit, $detik); // Format jam:menit:detik
                                    return $formattedTime; // Misalnya, '08:10:00' jika total menitnya 490
                                }
                            ],


                        ],
                    ]
                ) ?>
            </div>


        </div>
        <div class="col-7">

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
                <div class="col-6">
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
                <div class="col-6">
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
                                    'format' => 'raw', // Menambahkan format raw agar HTML ditampilkan
                                ],
                            ],
                        ]
                    ) ?>
                </div>

            </div>


            <?php $form = ActiveForm::begin(); ?>

            <?php echo  $form->field($model, 'kode_karyawan')->hiddenInput(['maxlength' => true, 'value' => $rekapandata['karyawan']['kode_karyawan'] ?? $model->kode_karyawan])->label(false)
            ?>
            <?php echo  $form->field($model, 'nomer_identitas')->hiddenInput(['maxlength' => true, 'value' => $rekapandata['karyawan']['nomer_identitas'] ?? $model->nomer_identitas])->label(false)
            ?>

            <?php echo  $form->field($model, 'nama')->hiddenInput(['maxlength' => true, 'value' => $rekapandata['karyawan']['nama'] ?? $model->nama])->label(false)
            ?>

            <?php echo  $form->field($model, 'bagian')->hiddenInput(['maxlength' => true, 'value' => $rekapandata['dataPekerjaan']['bagian']['nama_bagian'] ?? $model->bagian])->label(false)
            ?>

            <?php echo  $form->field($model, 'jabatan')->hiddenInput(['maxlength' => true, 'value' => $rekapandata['dataPekerjaan']['jabatan']['nama_kode'] ?? $model->jabatan])->label(false)
            ?>

            <?php echo  $form->field($model, 'jam_kerja')->hiddenInput(['value' => $rekapandata['karyawan']['id_jam_kerja'] ?? $model->jam_kerja])->label(false)
            ?>

            <?php echo  $form->field($model, 'status_karyawan')->hiddenInput(['maxlength' => true, 'value' => $rekapandata['dataPekerjaan']['statusKaryawan']['nama_kode'] ?? $model->status_karyawan])->label(false)
            ?>
            <?php echo  $form->field($model, 'periode_gaji')->hiddenInput(['maxlength' => true, 'value' => $rekapandata['periode_gaji']['id_periode_gaji']])->label(false)
            ?>

            <?php echo  $form->field($model, 'jumlah_hari_kerja')->hiddenInput(['value' => $rekapandata['karyawan']['total_hari_kerja'] ?? $model->jumlah_hari_kerja])->label(false)
            ?>

            <?php echo  $form->field($model, 'jumlah_hadir')->hiddenInput(['value' => $rekapandata['absensiData']['total_hadir'] ?? $model->jumlah_hadir])->label(false)
            ?>

            <?php echo  $form->field($model, 'jumlah_sakit')->hiddenInput(['value' => $rekapandata['absensiData']['total_sakit'] ?? $model->jumlah_sakit])->label(false)
            ?>

            <?php echo  $form->field($model, 'jumlah_wfh')->hiddenInput(['value' => $rekapandata['absensiData']['total_wfh'] ?? $model->jumlah_wfh])->label(false)
            ?>

            <?php echo  $form->field($model, 'jumlah_cuti')->hiddenInput(['value' => $rekapandata['totalCuti'] ?? $model->jumlah_cuti])->label(false)
            ?>

            <?php echo  $form->field($model, 'gaji_pokok')->hiddenInput(['maxlength' => true, 'value' => $rekapandata['gajiPokok']['nominal_gaji'] ?? $model->gaji_pokok])->label(false)
            ?>

            <?php

            if ($model->isNewRecord) {
                $data = $rekapandata['jumlahJamLembur']['total_menit'] / 60;
            } else {
                $data = $model->jumlah_jam_lembur;
            }

            echo  $form->field($model, 'jumlah_jam_lembur')->hiddenInput(['maxlength' => true, 'value' =>  $data])->label(false)
            ?>

            <?php

            if ($model->isNewRecord) {
                $data = 0;
            } else {
                $data = $model->lembur_perjam;
            }


            echo  $form->field($model, 'lembur_perjam')->textInput(['maxlength' => true, 'value' => $data, 'id' => "lembur_perjam", 'value' => $model->lembur_perjam ?? 0])->label("Bayaran Lembur perjam <span class='text-danger'>(Rp)</span>")
            ?>

            <?php

            if ($model->isNewRecord) {
                $data = 0;
            } else {
                $data = $model->total_lembur;
            }
            echo  $form->field($model, 'total_lembur')->textInput(['maxlength' => true,  'value' => $data, 'readonly' => true, 'id' => "total_lembur"])->label("Bayaran Lembur Diterima <span class='text-danger'>(Rp)</span>") ?>


            <?php

            if ($model->isNewRecord) {
                $data = $rekapandata['getTunjangan'];
            } else {
                $data = $model->jumlah_tunjangan;
            }
            echo $form->field($model, 'jumlah_tunjangan')->hiddenInput(['maxlength' => true, 'value' => $data, 'readonly' => true])->label(false) ?>

            <?php
            if ($model->isNewRecord) {
                $data = $rekapandata['getPotongan'];
            } else {
                $data = $model->jumlah_potongan;
            }
            echo $form->field($model, 'jumlah_potongan')->hiddenInput(['maxlength' => true, 'value' => $data, 'readonly' => true])->label(false) ?>

            <?php
            if ($model->isNewRecord) {
                $data = 0;
            } else {
                $data = $model->potongan_wfh_hari;
            }
            echo $form->field($model, 'potongan_wfh_hari')->textInput(['maxlength' => true, 'value' => $data, 'id' => "potongan_wfh"])->label("Potongan WFH Per Hari <span class='text-danger'>(Rp)</span>") ?>

            <?php
            if ($model->isNewRecord) {
                $data = 0;
            } else {
                $data = $model->jumlah_potongan_wfh;
            }
            echo $form->field($model, 'jumlah_potongan_wfh')->textInput(['maxlength' => true, 'readonly' => true, 'value' => 0, 'id' => "jumlah_potongan_wfh"])->label("Jumlah Potongan WFH <span class='text-danger'>(Rp)</span>") ?>

            <?php
            if ($model->isNewRecord) {
                $data = 0;
            } else {
                $data = $model->gaji_diterima;
            }
            echo $form->field($model, 'gaji_diterima')->textInput(['id' => "gaji_diterima", 'maxlength' => true, 'readonly' => true])->label("Gaji Diterima Karyawan <span class='text-danger'>(Rp)</span>");
            echo "<p class='text-muted text-xs mt-0'>Gaji Yang diterima sudah termasuk kalkulasi potongan karyawan & WFH, tunjangan dan lembur</p>"
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
    $dataToday = ArrayHelper::toArray($rekapandata);
    $rekap = json_encode($dataToday, JSON_PRETTY_PRINT);
    ?>
    <script>
        let todayJson = <?= $rekap ?>;
        let gaji_pokok = todayJson?.gajiPokok?.nominal_gaji;
        let potonganWFH = 0;
        let tambahanLembur = 0;
        let jumlahTunjangan = todayJson?.getTunjangan;
        let jumlahPotongan = todayJson?.getPotongan;


        function gajiDiterima() {
            gaji_diterima = gaji_pokok + tambahanLembur + jumlahTunjangan - jumlahPotongan - potonganWFH;
            // console.info(gaji_diterima)
            $('#gaji_diterima').val(gaji_diterima);
        }

        gajiDiterima();
        let totalLembur = 0;
        let lemburPerjam = todayJson.jumlahJamLembur.total_menit / 60;
        $('#lembur_perjam').keyup(function(e) {
            e.preventDefault();
            tambahanLembur = Number(this.value) * lemburPerjam;
            $('#total_lembur').val(Number(this.value) * lemburPerjam)
            gajiDiterima();

        });

        // potongan wfh
        $('#potongan_wfh').keyup(function(e) {
            e.preventDefault();
            potonganWFH = Number(this.value) * Number(todayJson?.absensiData?.total_wfh)
            $('#jumlah_potongan_wfh').val(Number(this.value) * Number(todayJson?.absensiData?.total_wfh))
            gajiDiterima();
        })
        //memnentukan pembayaran lembur


        //fungsi mencari gaji diterima
    </script>

</div>