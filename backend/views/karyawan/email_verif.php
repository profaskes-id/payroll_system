<?php

use backend\models\MasterKode;

$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: "Source Sans Pro", sans-serif;
            background-color: #f5f6fa;
            margin: 0;
            padding: 0;
        }

        .center-card {
            max-width: 700px;
            margin: 40px auto;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .content-card {
            background-color: #ffffff;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .login-link {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            color: #ffffff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .login-link:hover {
            background-color: #0056b3;
        }

        .card-body p {
            line-height: 1.8;
            font-size: 16px;
            color: #333;
        }

        img {
            max-width: 100%;
            border-radius: 10px 10px 0 0;
        }
    </style>
</head>

<body>
    <div class="center-card">
        <div class="card-header">
            Akses Payroll Profaskes
        </div>
        <picture>
            <source media="(max-width: 768px)" srcset="https://www.profaskes.id/images/others/email-phone.jpg">
            <source media="(min-width: 769px)" srcset="https://www.profaskes.id/images/others/email.jpg">
            <img src="https://www.profaskes.id/images/others/email-phone.jpg" alt="Ilustrasi Email">
        </picture>

        <div class="content-card">
            <div class="card-body">
                <p>Yth. Pengguna,</p>

                <p>Anda telah didaftarkan dalam sistem payroll <strong>Profaskes.id</strong>. Berikut adalah tautan untuk mengakses akun Anda:</p>

                <a class="login-link" href="<?= Yii::getAlias('@root') . '/panel/auto-login/login?token=' . $ciphertext . '&id=' . $model->kode_karyawan ?>">
                    Klik di sini untuk Login
                </a>

                <p>Setelah berhasil login, harap segera mengganti kata sandi Anda melalui menu profil untuk menjaga keamanan akun.</p>

                <p>Terima kasih atas perhatian Anda.</p>

                <p>Hormat kami,<br>Admin Profaskes</p>
            </div>
        </div>
    </div>
</body>

</html>