<?php

use backend\models\Tanggal;

$tanggalFormat = new Tanggal();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>Perjanjian Kerja Waktu Tertentu</title> -->
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
</head>

<body class="text-justify" style="text-align: justify !important;">
    <div class="container mt-5 content ">
        <h3 class="text-center " style="font-weight: bold; font-size: 16px; text-decoration: underline;">PERJANJIAN KERJA WAKTU TERTENTU</h3>
        <p class="text-center" style="font-size: 14px;">Nomor : <?= $model['nomor_surat'] ?></p>
        <p style="font-size: 12px">Perjanjian ini adalah antara : </p>
        <p class="text-justify" style="text-align: justify !important; font-size: 12px;"><strong style="font-weight: 600;"><?= $model['nama_penanda_tangan'] ?></strong>, dalam hal ini bertindak atas jabatannya sebagai <?= $model['jabatan_penanda_tangan'] ?> <?= $perusahaan['nama_perusahaan'] ?>, sebuah perusahaan yang bergerak di bidang <?= $perusahaan['bidang_perusahaan'] ?>. <?= $perusahaan['nama_perusahaan'] ?> yang berkedudukan di <?= $perusahaan['alamat'] ?>, Untuk selanjutnya disebut sebagai <strong>PIHAK PERTAMA</strong>.</p>

        <p style="font-size: 12px;">Dengan : </p>
        <p style="font-size: 12px;">
        <table>
            <tr>
                <td style="font-size: 12px;">Nama</td>
                <td style="vertical-align: top; text-align:center; width:25px;">:</td>
                <td style="text-transform: capitalize; font-size:12px;"><?= $karyawan['nama'] ?></td>
            </tr>
            <tr>
                <td style="font-size: 12px;">Tempat, Tgl Lahir:</td>
                <td style="vertical-align: top; text-align:center; width:25px;">:</td>
                <td style="text-transform: capitalize; font-size:12px;"><?= $karyawan['tempat_lahir']  ?>, <?= $tanggalFormat->getIndonesiaFormatTanggal($karyawan['tanggal_lahir']);  ?></td>
            </tr>
            <tr>
                <td style="font-size: 12px;">Nomor Identitias</td>
                <td style="vertical-align: top; text-align:center; width:25px;">:</td>
                <td style="font-size: 12px;"><?= $karyawan['nomer_identitas'] ?></td>
            </tr>
            <tr>
                <td style="font-size: 12px;">Jenis Identitas</td>
                <td style="vertical-align: top; text-align:center; width:25px;">:</td>
                <td style="font-size: 12px;"><?= $karyawan->jenisidentitas->nama_kode ?></td>
            </tr>
            <tr>
                <td style="font-size: 12px;">Jenis Kelamin</td>
                <td style="vertical-align: top; text-align:center; width:25px;">:</td>
                <td style="font-size: 12px;"><?= $karyawan->kode_jenis_kelamin == 'L' || $karyawan->kode_jenis_kelamin == '1' ? 'Laki-Laki' : 'Perempuan' ?></td>
            </tr>
            <tr>
                <td style="font-size: 12px;">Status Menikah</td>
                <td style="vertical-align: top; text-align:center; width:25px;">:</td>
                <td style="font-size: 12px;"><?= $karyawan->statusNikah->nama_kode ?></td>
            </tr>
            <tr>
                <td style="font-size: 12px; vertical-align: top;">Alamat</td>
                <td style="vertical-align: top; text-align:center; width:25px;">:</td>
                <td style="font-size: 12px; text-transform: capitalize;">
                    <?php if ($karyawan->is_current_domisili == 1) :  ?>
                        <?= strtolower($karyawan['alamat_identitas']) . ', ' . strtotime($karyawan['desa_lurah_identitas']) . ', ' . strtolower($karyawan->kecamatanidentitas->nama_kec), ', ' . strtolower($karyawan->kabupatenidentitas->nama_kab) . ', ' . strtolower($karyawan->provinsiidentitas->nama_prop) ?> <br>
                    <?php else :  ?>
                        <?= strtolower($karyawan['alamat_domisili']) . ' ' .  strtolower($karyawan['desa_lurah_domisili']) . ', ' . strtolower($karyawan->kecamatandomisili->nama_kec), ', ' . strtolower($karyawan->kabupatendomisili->nama_kab) . ', ' . strtolower($karyawan->provinsidomisili->nama_prop) ?> <br>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
        </p>

        <p style="font-size: 12px;">

            Untuk selanjutnya disebut sebagai <strong style="font-weight: 500;">PIHAK KEDUA</strong>.
        </p>
        <h5 class="my-4 text-justify" style="font-size: 12px;">Dengan ini pihak pertama menerima pihak kedua untuk bekerja di <?= $perusahaan['nama_perusahaan'] ?> dengan kondisi-kondisi sebagai berikut:</h5>

        <table class="">
            <tr class="pb-5">
                <td class="w-25" style="font-size: 12px; vertical-align: top !important;">STATUS KARYAWAN</td>
                <td class="w-25" style="font-size: 12px; vertical-align: top; width:30px; text-align:center;"><strong>:</strong></td>
                <td style="font-size: 12px;">Karyawan PKWT</td>
            </tr>
            <tr class="text-justify pb-5">
                <td class="w-25" style="font-size: 12px; vertical-align: top !important;">MASA KONTRAK</td>
                <td class="w-25" style="font-size: 12px; vertical-align: top; width:30px; text-align:center;"><strong>:</strong></td>
                <td class="text-justify" style="font-size: 12px;">Mulai dari <?= $tanggalFormat->getIndonesiaFormatTanggal($dataPekerjaan['dari']) ?> sampai dengan
                    <?php if ($dataPekerjaan['sampai']) :  ?>
                        <?= $tanggalFormat->getIndonesiaFormatTanggal($dataPekerjaan['sampai']) ?>.
                    <?php else :  ?>
                        <?= 'Sekarang' ?>
                    <?php endif; ?>
                    <br> <em style="font-size: 12px;">(Jika diperlukan berlaku perpanjangan otomatis untuk satu tahun berikutnya). </em>
                </td>
            </tr>
            <tr class="pb-5">
                <td class="w-25" style="font-size: 12px; vertical-align: top !important;">POSISI</td>
                <td class="w-25" style="font-size: 12px; vertical-align: top; width:30px; text-align:center;"><strong>:</strong></td>
                <td style="font-size: 12px;"><?= $dataPekerjaan->jabatanPekerja->nama_kode ?? '' ?></td>
            </tr>

            <tr class="pb-5">
                <td class="w-25" style="font-size: 12px; vertical-align: top !important;">HAK PIHAK KEDUA</td>
                <td class="w-25" style="font-size: 12px; vertical-align: top; width:30px; text-align:center;"><strong>:</strong></td>
                <td style="font-size: 12px;">
                    <ol style="padding-left: 20px;" style="text-align: justify;  font-size: 12px;  padding-left: 0px;">
                        <li style="font-size:12px; text-align: justify;">Mendapat gaji pokok, transport tunjangan jabatan, bonus kinerja.</li>
                        <li style="font-size:12px; text-align: justify;">Mendapat fasilitas BPJS Kesehatan dan BPJS Ketenagakerjaan.</li>
                        <li style="font-size:12px; text-align: justify; ">
                            <span>
                                Mendapat tunjangan hari raya keagamaan sebesar 1 (satu) bulan gaji.
                            </span>
                            <p>&nbsp; &nbsp; Apabila pihak kedua masa kerjanya belum mencapai 1 (satu) tahun pada
                            </p>
                            <p>
                                &nbsp; &nbsp; saat hari raya keagamaan dibayarkan, berhak mendapatkan 1/12
                            </p>
                            <p>
                                &nbsp; &nbsp; (seperduabelas) dari tunjangan hari raya untuk setiap bulan masa kerja.
                            </p>
                        </li>
                        <li style="text-align: justify;">Bekerja WFH mendapatkan potongan sesuai ketentuan perusahaan.</li>
                        <li style="text-align: justify;">Pajak yang timbul atas gaji ditanggung oleh karyawan.</li>
                    </ol>
                </td>
            </tr>

            <tr>
                <td colspan="3" style="height: 10px;">
                </td>
            </tr>

            <tr>
                <td class="w-25" style="font-size: 12px; vertical-align: top !important;">KEWAJIBAN PIHAK KEDUA</td>
                <td class="w-25" style="font-size: 12px; vertical-align: top; width:30px; text-align:center;"><strong>:</strong></td>
                <td>
                    <ol style="font-size: 12px; text-align: justify;">
                        <li style="font-size: 12px; text-align: justify;">Hadir di kantor sesuai waktu & jam kerja yang berlaku di perusahaan.</li>
                        <li style="font-size: 12px; text-align: justify;">
                            <span>Menjalankan pekerjaan sesuai uraian tugas, tanggungjawab </span>
                            <p>
                                &nbsp; &nbsp; dan wewenang yang diberikan.
                            </p>

                        </li>
                        <li style="font-size: 12px; text-align: justify;">Menjaga nama baik dan rahasia perusahaan.</li>
                        <li style="font-size: 12px; text-align: justify;">Mematuhi kode etik yang berlaku di perusahaan.</li>
                        <li style="font-size: 12px; text-align: justify;">Menjaga kerahasiaan data publik sesuai UU nomor 27 Tahun 2022 tentang
                        </li>
                        <p style="font-size: 12px;">&nbsp; &nbsp; Perlindungan data pribadi.</p>
                        <li style="font-size: 12px; text-align: justify;">Menjaga kerahasiaan data medis berdasarkan Peraturan Menteri </li>

                        <p style="font-size: 12px;">&nbsp; &nbsp; Kesehatan Nomor 24 tahun 2022 tentang Rekam Medis.</p>
                        <li style="font-size: 12px; text-align: justify;">Dilarang mengikat hubungan kerja dengan perusahaan lain.</li>
                        <li style="font-size: 12px; text-align: justify;">Dilarang menerima komisi dari pembelian atau jasa untuk kepentingan
                            <p style="font-size: 12px;">&nbsp; &nbsp; pribadi.</p>
                        </li>
                    </ol>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="height: 10px;">
                </td>
            </tr>
            <tr class="pb-5">
                <td class="w-25" style="font-size: 12px; vertical-align: top !important;">PEMUTUSAN HUBUNGAN KERJA</td>
                <td class="w-25" style="font-size: 12px; vertical-align: top; width:30px; text-align:center;"><strong>:</strong></td>
                <td style="font-size: 12px;">
                    <ol style="font-size: 12px;">
                        <li style="font-size: 12px;">Pihak pertama maupun pihak kedua dapat melakukan </li>
                        <p style="font-size: 12px;">&nbsp; &nbsp;
                            pemutusan hubungan kerja sewaktu-waktu dengan pemberitahuan
                        </p>
                        <p style="font-size: 12px;">&nbsp; &nbsp;
                            (tiga puluh) hari sebelumnya.
                        </p>
                        <li style="font-size: 12px;">Pemutusan hubungan kerja yang tidak sesuai prosedur maka akan </li>
                        <p style="font-size: 12px;">&nbsp; &nbsp;&nbsp;membatalkan hak-hak para pihak.</p>
                    </ol>
                </td>
            </tr>

            <tr class="pb-5">
                <td class="w-25" style="font-size: 12px; vertical-align: top;">IJIN MENINGGALKAN KERJA</td>
                <td class="w-25" style="font-size: 12px; vertical-align: top; width:30px; text-align:center;"><strong>:</strong></td>
                <td style="font-size: 12px;">
                    <ol style="font-size: 12px;">
                        <li style="font-size: 12px;">Pihak kedua boleh meninggalkan kerja tanpa pemotongan gaji untuk </li>
                        <p style="font-size: 12px;">&nbsp; &nbsp;
                            keperluan :
                        </p>
                        <ul style="font-size: 12px;">
                            <li style="font-size: 12px;">Sakit yang dibuktikan dengan surat dokter.</li>
                            <li style="font-size: 12px;">Menikah: 3 (tiga) hari kerja.</li>
                            <li style="font-size: 12px;">Menikahkan anaknya/mengkhitankan anaknya/membaptiskan anaknya </li>
                            <p style="font-size: 12px;">&nbsp; &nbsp; &nbsp; 2 (dua) hari kerja. </p>
                            <li style="font-size: 12px;">Isteri melahirkan atau keguguran kandungan: 2 (dua) hari kerja.</li>
                            <li style="font-size: 12px;">Orang tua/Mertua/Anak/Suami/Istri meninggal dunia: 2 (dua) hari kerja.</li>
                            <li style="font-size: 12px;">Anggota keluarga dalam satu rumah meninggal dunia: 1 (satu) hari kerja.</li>
                        </ul>

                        <li style="font-size: 12px;">Meninggalkan kerja di luar kepentingan di atas dapat diperoleh
                        </li>
                        <p style="font-size: 12px;">&nbsp; &nbsp;
                            jika ada kepentingan yang sangat mendesak dengan dilakukan
                        </p>
                        <p style="font-size: 12px;">&nbsp; &nbsp;
                            pemotongan gaji sesuai jumlah hari ketidakhadiran.
                        </p>
                    </ol>
                </td>
            </tr>
        </table>

        <p class="text-justify mt-5" style="font-size: 12px;">Hal-hal lain yang tidak dinyatakan dalam kesepakatan kerja ini mengikuti peraturan <?= $perusahaan['nama_perusahaan'] ?> dan sesuai dengan peraturan ketenagakerjaan yang berlaku.</p>

        <p class="text-justify" style="font-size: 12px;">Kedua belah pihak dengan ini menyatakan persetujuan atas persyaratan yang tercantum dalam kesepakatan kerja ini.</p>

        <p class="text-left text-uppercase" style="font-size: 12px;"><?= $model['tempat_dan_tanggal_surat'] ?></p>

        <br>
        <table class="w-100 table " style="font-size: 12px;">
            <tr>
                <td>
                    <div class="">
                        <p style="font-size: 12px;">PIHAK PERTAMA</p>
                        <br><br><br><br><br><br>
                        <p style="font-size: 12px; font-weight: bold;"><strong><?= $model['nama_penanda_tangan'] ?></strong></p>
                        <p style="  text-decoration: none; font-size:12px; "><?= "({$model['jabatan_penanda_tangan']})" ?></p>
                    </div>

                </td>
                <td class="text-center">
                    <div>
                        <p style="font-size: 12px;">PIHAK KEDUA</p>
                        <br><br><br><br><br><br>
                        <p style="font-size: 12px; font-weight: bold;"><strong style="font-size: 12px; text-transform: capitalize;"><?= $karyawan['nama'] ?> </strong></p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>