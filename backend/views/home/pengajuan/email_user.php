<?php

$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: "Roboto Condensed", sans-serif;
            background: rgba(0, 0, 0, 0.05);
        }

        .center-card {
            width: 100%;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .card-header {
            margin: 30px auto;
            padding-top: 20px;
            width: 100%;
            max-width: 800px;
            background-color: #0d6efd;
            color: white;
            text-align: center;
            padding-bottom: 10px;
            border-radius: 10px;
        }

        .content-card {
            border-radius: 10px;
            background-color: #fff;
            width: 100%;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .card-body {
            padding: 20px;
        }

        .imgbox {
            width: 90%;
            margin: auto;
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <div class="row">
        <div class="mx-auto center-card col-md-8"> <!-- Tambahkan kelas mx-auto untuk menengahkan card -->
            <div class="card card-info card-outline">
                <picture>
                    <source media="(max-width: 768px)" srcset="https://profaskes.id/images/others/email-phone.jpg">
                    <source media="(min-width: 769px)" srcset="https://profaskes.id/images/others/email.jpg">
                    <img src="https://profaskes.id/images/others/email-phone.jpg" alt="Gambar default">
                </picture>

                <div class="content-card">
                    <div class="card-body">
                        <h2 style="text-transform: capitalize;">Pengajuan Baru dari Karyawan</h2>

                        <p>
                            Yth. Tim HRD,
                            <br />
                            Kami ingin memberitahukan bahwa karyawan <strong><?= $model->karyawan->nama ?></strong> telah mengajukan permohonan </strong>.
                            <br />
                            <br />
                            Berikut adalah detail pengajuan:
                        </p>

                        <p>
                            <strong>Judul Pengajuan:</strong> <?= $params['judul'] ?><br />
                            <strong>Deskripsi:</strong> <?= $params['deskripsi'] ?><br />
                            <strong>Halaman untuk melihat pengajuan:</strong>
                            <a href="<?= Yii::getAlias('@root') . $params['nama_transaksi'] . '=' . $params['id_transaksi'] ?>">
                                Lihat Pengajuan
                            </a>
                        </p>

                        <p>
                            Kami mohon agar pengajuan ini dapat diproses secepatnya.
                            Terima kasih atas perhatian Anda. </p>


                        <p>Hormat kami,<br />System Profaskes</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>