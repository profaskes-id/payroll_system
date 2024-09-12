<?php

use backend\models\MasterKode;

$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback');

// $whatsappMasterCode = MasterKode::findOne(['group' => 'whatsapp', 'kode' => 'WA01']);
// $whatsappAdmin = $whatsappMasterCode->kode_name ?? '';
// $emailMasterCode = MasterKode::findOne(['group' => 'email', 'kode' => 'EM01']);
// $emailAdmin = $emailMasterCode->kode_name ?? '';
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
        <div class="center-card col-md-8 mx-auto"> <!-- Tambahkan kelas mx-auto untuk menengahkan card -->
            <div class="card card-info card-outline">
                <picture>
                    <source media="(max-width: 768px)" srcset="https://www.profaskes.id/images/others/email-phone.jpg">
                    <source media="(min-width: 769px)" srcset="https://www.profaskes.id/images/others/email.jpg">
                    <img src="https://www.profaskes.id/images/others/email-phone.jpg" alt="Gambar default">
                </picture>

                <div class="content-card">
                    <div class="card-body">
                        <h2 style="text-transform : capitalize;">Akses Payroll Profaskes </h2>

                        <p>
                            Dear,
                            <br />
                            <br />
                            Anda telah di daftarkan dalan sistem payroll profaskes.id, berikut adalah akses masuk ke akun anda
                            <br />
                            <br />
                        </p>

                        <p>Anda Dapat Login Menggunakan token berikut</p>
                        <br>
                        <?= Yii::getAlias('@root') . '/panel/auto-login/login?token=' . $user->access_token ?>
                        <br />
                        harap untuk melakukan penggantian password di profil aplikasi anda demi keamanan akun anda

                        Terima kasih atas perhatian Anda dan selamat menggunakan layanan kami.
                        </p>

                        <p>Admin Profaskes</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>