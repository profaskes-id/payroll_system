<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>Perjanjian Kerja Waktu Tertentu</title> -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5 content">
        <h3 class="text-center">PERJANJIAN KERJA WAKTU TERTENTU</h3>
        <p class="text-center"><?= $model['nomor_surat'] ?></p>
        <p>Perjanjian ini adalah antara:</p>
        <p><strong><?= $model['nama_penanda_tangan'] ?></strong>, dalam hal ini bertindak atas jabatannya sebagai <?= $model['jabatan_penanda_tangan'] ?> <?= $perusahaan['nama_perusahaan'] ?>, sebuah perusahaan yang bergerak di bidang jasa penyedia aplikasi kesehatan <?= $perusahaan['nama_perusahaan'] ?> yang berkedudukan di <?= $perusahaan['alamat'] ?>, Untuk selanjutnya disebut sebagai <strong>PIHAK PERTAMA</strong>.</p>

        <p>Dengan:</p>
        <p>
        <table>
            <tr>
                <td>Nama</td>
                <td style="vertical-align: top; text-align:center; width:25px;">:</td>
                <td style="text-transform: capitalize;"><?= $karyawan['nama'] ?></td>
            </tr>
            <tr>
                <td>Tempat, Tgl Lahir:</td>
                <td style="vertical-align: top; text-align:center; width:25px;">:</td>
                <td style="text-transform: capitalize;"><?= $karyawan['tempat_lahir'] ?>, <?= date('d F Y', strtotime($karyawan['tanggal_lahir'])) ?></td>
            </tr>
            <tr>
                <td>Nomor Identitias</td>
                <td style="vertical-align: top; text-align:center; width:25px;">:</td>
                <td><?= $karyawan['nomer_identitas'] ?></td>
            </tr>
            <tr>
                <td>Jenis Identitas</td>
                <td style="vertical-align: top; text-align:center; width:25px;">:</td>
                <td><?= $karyawan->jenisidentitas->nama_kode ?></td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td style="vertical-align: top; text-align:center; width:25px;">:</td>
                <td><?= $karyawan->kode_jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' ?></td>
            </tr>
            <tr>
                <td>Status Menikah</td>
                <td style="vertical-align: top; text-align:center; width:25px;">:</td>
                <td><?= $karyawan->statusNikah->nama_kode ?></td>
            </tr>
            <tr>
                <td style="vertical-align: top;">Alamat</td>
                <td style="vertical-align: top; text-align:center; width:25px;">:</td>
                <td>
                    <?php if ($karyawan->is_current_domisili == 1) :  ?>
                        <?= $karyawan['alamat_identitas'] . ', ' . $karyawan['desa_lurah_identitas'] . ', ' . $karyawan->kecamatanidentitas->nama_kec, ', ' . $karyawan->kabupatenidentitas->nama_kab . ', ' . $karyawan->provinsiidentitas->nama_prop ?> <br>
                    <?php else :  ?>
                        <?= $karyawan['alamat_domisili'] . ' ' . $karyawan['desa_lurah_domisili'] . ', ' . $karyawan->kecamatandomisili->nama_kec, ', ' . $karyawan->kabupatendomisili->nama_kab . ', ' . $karyawan->provinsidomisili->nama_prop ?> <br>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
        </p>

        <p>

            Untuk selanjutnya disebut sebagai <strong>PIHAK KEDUA</strong>.
        </p>
        <h5 class="my-5">Dengan ini pihak pertama menerima pihak kedua untuk bekerja di <?= $perusahaan['nama_perusahaan'] ?> dengan kondisi-kondisi sebagai berikut:</h5>

        <table class="">
            <tr class="pb-5">
                <td class="w-25" style="vertical-align: top !important;">STATUS KARYAWAN</td>
                <td class="w-25" style="vertical-align: top; width:30px; text-align:center;"><strong>:</strong></td>
                <td>Karyawan PKWT</td>
            </tr>
            <tr class="pb-5">
                <td class="w-25" style="vertical-align: top !important;">MASA KONTRAK</td>
                <td class="w-25" style="vertical-align: top; width:30px; text-align:center;"><strong>:</strong></td>
                <td>Mulai dari <?= date('d F Y', strtotime($dataPekerjaan['dari'])) ?> sampai dengan
                    <?php if ($dataPekerjaan['sampai']) :  ?>
                        <?= date('d F Y', strtotime($dataPekerjaan['sampai'])) ?>.
                    <?php else :  ?>
                        <?= 'Sekarang' ?>
                    <?php endif; ?>
                    <br> <em style="font-size: 12px;">(Jika diperlukan berlaku perpanjangan otomatis untuk satu tahun berikutnya). </em>
                </td>
            </tr>
            <tr class="pb-5">
                <td class="w-25" style="vertical-align: top !important;">POSISI</td>
                <td class="w-25" style="vertical-align: top; width:30px; text-align:center;"><strong>:</strong></td>
                <td>Technical Support </td>
            </tr>

            <tr class="pb-5">
                <td class="w-25" style="vertical-align: top !important;">HAK PIHAK KEDUA</td>
                <td class="w-25" style="vertical-align: top; width:30px; text-align:center;"><strong>:</strong></td>
                <td>
                    <ul style="padding-left: 20px;">
                        <li>Mendapat gaji pokok, transport tunjangan jabatan, bonus kinerja.</li>
                        <li>Mendapat fasilitas BPJS Kesehatan dan BPJS Ketenagakerjaan.</li>
                        <li>Mendapat tunjangan hari raya keagamaan sebesar 1 (satu) bulan gaji. Apabila pihak kedua masa kerjanya belum mencapai 1 (satu) tahun pada saat hari raya keagamaan dibayarkan, berhak mendapatkan 1/12 (seperduabelas) dari tunjangan hari raya untuk setiap bulan masa kerja.</li>
                        <li>Bekerja WFH mendapatkan potongan sesuai ketentuan perusahaan.</li>
                        <li>Pajak yang timbul atas gaji ditanggung oleh karyawan.</li>
                    </ul>
                </td>
            </tr>
            <tr class="pb-5">
                <td class="w-25" style="vertical-align: top !important;">KEWAJIBAN PIHAK KEDUA</td>
                <td class="w-25" style="vertical-align: top; width:30px; text-align:center;"><strong>:</strong></td>
                <td>
                    <ul type="square">
                        <li>Hadir di kantor sesuai waktu & jam kerja yang berlaku di perusahaan.</li>
                        <li>Menjalankan pekerjaan sesuai uraian tugas, tanggungjawab dan wewenang yang diberikan.</li>
                        <li>Menjaga nama baik dan rahasia perusahaan.</li>
                        <li>Mematuhi kode etik yang berlaku di perusahaan.</li>
                        <li>Menjaga kerahasiaan data publik sesuai UU nomor 27 Tahun 2022 tentang Perlindungan data pribadi.</li>
                        <li>Menjaga kerahasiaan data medis berdasarkan Peraturan Menteri Kesehatan Nomor 24 tahun 2022 tentang Rekam Medis.</li>
                        <li>Dilarang mengikat hubungan kerja dengan perusahaan lain.</li>
                        <li>Dilarang menerima komisi dari pembelian atau jasa untuk kepentingan pribadi.</li>
                    </ul>
                </td>
            </tr>
            <tr class="pb-5">
                <td class="w-25" style="vertical-align: top !important;">PEMUTUSAN HUBUNGAN KERJA</td>
                <td class="w-25" style="vertical-align: top; width:30px; text-align:center;"><strong>:</strong></td>
                <td>
                    <ul type="disc">
                        <li>Pihak pertama maupun pihak kedua dapat melakukan pemutusan hubungan kerja sewaktu-waktu dengan pemberitahuan 30 (tiga puluh) hari sebelumnya.</li>
                        <li>Pemutusan hubungan kerja yang tidak sesuai prosedur maka akan membatalkan hak-hak para Pihak.</li>
                    </ul>
                </td>
            </tr>
            <tr class="pb-5">
                <td class="w-25" style="vertical-align: top;">IJIN MENINGGALKAN KERJA</td>
                <td class="w-25" style="vertical-align: top; width:30px; text-align:center;"><strong>:</strong></td>
                <td>
                    <ul>
                        <li>Pihak kedua boleh meninggalkan kerja tanpa pemotongan gaji untuk keperluan:</li>
                        <ul>
                            <li>Sakit yang dibuktikan dengan surat dokter.</li>
                            <li>Menikah: 3 (tiga) hari kerja.</li>
                            <li>Menikahkan anaknya/mengkhitankan anaknya/membaptiskan anaknya: 2 (dua) hari kerja.</li>
                            <li>Isteri melahirkan atau keguguran kandungan: 2 (dua) hari kerja.</li>
                            <li>Orang tua/Mertua/Anak/Suami/Istri meninggal dunia: 2 (dua) hari kerja.</li>
                            <li>Anggota keluarga dalam satu rumah meninggal dunia: 1 (satu) hari kerja.</li>
                        </ul>
                        <li>Meninggalkan kerja di luar kepentingan di atas dapat diperoleh jika ada kepentingan yang sangat mendesak dengan dilakukan pemotongan gaji sesuai jumlah hari ketidakhadiran.</li>
                    </ul>
                </td>
            </tr>
        </table>

        <p>Hal-hal lain yang tidak dinyatakan dalam kesepakatan kerja ini mengikuti peraturan <?= $perusahaan['nama_perusahaan'] ?> dan sesuai dengan peraturan ketenagakerjaan yang berlaku.</p>

        <p>Kedua belah pihak dengan ini menyatakan persetujuan atas persyaratan yang tercantum dalam kesepakatan kerja ini.</p>

        <p class="text-left"><?= $model['tempat_dan_tanggal_surat'] ?></p>

        <br>
        <table class="w-100 table ">
            <tr>
                <td>
                    <div class="">
                        <p>PIHAK PERTAMA</p>
                        <br><br><br>
                        <p><strong><?= $model['nama_penanda_tangan'] ?></strong></p>
                        <p style="text-decoration: none; font-size:14px; "><?= "({$model['jabatan_penanda_tangan']})" ?></p>
                    </div>

                </td>
                <td class="text-center">
                    <div>
                        <p>PIHAK KEDUA</p>
                        <br><br><br>
                        <p><strong style="text-transform: capitalize;"><?= $karyawan['nama'] ?> </strong></p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>