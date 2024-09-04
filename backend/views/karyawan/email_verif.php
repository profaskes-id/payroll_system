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
                        <h2 style="text-transform : capitalize;">akses akun trial profaskes </h2>

                        <p>
                            Dear,
                            <br />
                            <br />
                            Terima kasih telah mendaftar di Trial Profaskes . Berikut adalah informasi akun Anda:
                            <br />
                            <br />
                        </p>
                        <p>

                        <table>
                            <tr>
                            <tr>
                                <td><strong>Email</strong></td>
                                <td><strong> : </strong></td>
                                <td><?= $model['email'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Username</strong></td>
                                <td><strong> : </strong></td>
                                <td><?= $model['nama'] ?? '' ?></td>
                            </tr>
                            <tr>
                                <td><strong>Password</strong></td>
                                <td><strong> : </strong></td>
                                <td><code style="font-size: 16px"><mark style="padding: 1px 3px;"><?= $model->id_karyawan . $model->kode_karyawan  . $model->jenis_identitas . $model->kode_jenis_kelamin ?></mark></code></td>
                            </tr>
                            <tr>
                                <td><strong>Halaman Login</strong></td>
                                <td><strong> : </strong></td>
                                <td><?= yii\helpers\Html::a('Login Disini', 'http://localhost:8080/panel/', ['target' => '_blank']) ?></td>
                            </tr>
                        </table>
                        </p>
                        <br>
                        <br>

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