<?php

namespace app\modules\v1\controllers;

use amnah\yii2\user\models\User;
use backend\models\Karyawan;
use yii\rest\Controller;
use yii\web\Response;

class AuthController extends Controller
{
    // Konfigurasi untuk mengembalikan JSON
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats'] = [
            'application/json' => Response::FORMAT_JSON
        ];
        return $behaviors;
    }

    public function actionLogin()
    {
        // Ambil raw body
        $rawBody = \Yii::$app->request->getRawBody();
        $data = json_decode($rawBody, true);


        // Validasi input
        if (!isset($data['email']) || !isset($data['password'])) {
            \Yii::$app->response->statusCode = 400;
            return [
                'status' => 'error',
                'message' => 'email dan password diperlukan'
            ];
        }

        $email = $data['email'];
        $password = $data['password'];

        // Debug: Cek kolom yang tersedia
        $columns = \Yii::$app->db->schema->getTableSchema('user')->columnNames;
        \Yii::info('Kolom yang tersedia: ' . print_r($columns, true), 'login');

        // Cari user di database dengan query dinamis
        $query = new \yii\db\Query();
        $user = $query->from('user')
            ->where(['email' => $email])
            ->one();

        if ($user) {
            // Coba beberapa kemungkinan nama kolom password
            $passwordColumns = ['password', 'password_hash', 'pwd', 'pass'];

            foreach ($passwordColumns as $passwordColumn) {
                if (isset($user[$passwordColumn])) {
                    if (\Yii::$app->security->validatePassword($password, $user[$passwordColumn])) {
                        // Generate token sederhana
                        $token = \Yii::$app->security->generateRandomString();

                        // Simpan token ke session
                        \Yii::$app->session->set('api_token_' . $user['id'], $token);
                        $karyawan = Karyawan::findOne(['email' => $user['email']]);
                        return [
                            'status' => 'success',
                            'message' => 'Login berhasil',
                            'access_token' => $token,
                            'user' => [
                                'id' => $user['id'],
                                'email' => $user['email'],
                                'id_karyawan' => $karyawan->id_karyawan,
                                'nama' => $karyawan->nama,
                                'kode_karyawan' => $karyawan->kode_karyawan,
                        'is_atasan' => $karyawan->is_atasan,

                            ],
                        ];
                    }
                    break;
                }
            }
        }

        \Yii::$app->response->statusCode = 401;
        return [
            'status' => 'error',
            'message' => 'email atau password salah'
        ];
    }

    // Contoh endpoint yang memerlukan autentikasi
    public function actionProtected()
    {
        // Ambil token dari header
        $token = \Yii::$app->request->headers->get('Authorization');

        if (!$token) {
            \Yii::$app->response->statusCode = 401;
            return [
                'status' => 'error',
                'message' => 'Token diperlukan'
            ];
        }

        // Cari user berdasarkan token yang tersimpan di session
        $userId = null;
        foreach (\Yii::$app->session->all() as $key => $value) {
            if (strpos($key, 'api_token_') === 0 && $value === $token) {
                $userId = str_replace('api_token_', '', $key);
                break;
            }
        }

        if (!$userId) {
            \Yii::$app->response->statusCode = 401;
            return [
                'status' => 'error',
                'message' => 'Token tidak valid'
            ];
        }

        // Cari user berdasarkan ID
        $user = User::findOne($userId);

        if (!$user) {
            \Yii::$app->response->statusCode = 401;
            return [
                'status' => 'error',
                'message' => 'User tidak ditemukan'
            ];
        }

        // Jika token valid, kembalikan data
        return [
            'status' => 'success',
            'message' => 'Anda berhasil mengakses endpoint yang dilindungi',
            'user' => [
                'id' => $user->id,
                'email' => $user->email
            ]
        ];
    }

public function actionUpdateProfile()
{
    // Tambahkan logging
    \Yii::info('Update Profile Request Data: ' . print_r($data, true));

    // Ambil raw body
    $rawBody = \Yii::$app->request->getRawBody();
    $data = json_decode($rawBody, true);

    // Validasi input
    if (!isset($data['id']) || !isset($data['current_password'])) {
        \Yii::$app->response->statusCode = 400;
        return [
            'status' => 'error',
            'message' => 'User ID dan current password diperlukan'
        ];
    }

    // Cari user berdasarkan ID
    $user = User::findOne($data['id']);
    if (!$user) {
        \Yii::$app->response->statusCode = 404;
        return [
            'status' => 'error',
            'message' => 'User tidak ditemukan'
        ];
    }

    // Set scenario
    $user->scenario = 'account';

    // Log informasi user
    \Yii::info('User Found - Stored Password: ' . $user->password);
    \Yii::info('Input Current Password: ' . $data['current_password']);

    // Validasi password manual dengan metode bawaan model
    $currentPasswordValid = $user->validatePassword($data['current_password']);
    
    if (!$currentPasswordValid) {
        \Yii::error('Password validation failed');
        \Yii::$app->response->statusCode = 401;
        return [
            'status' => 'error',
            'message' => 'Password saat ini salah'
        ];
    }

    // Cari karyawan berdasarkan email user
    $karyawan = Karyawan::findOne(['email' => $user->email]);

    if (!$karyawan) {
        \Yii::$app->response->statusCode = 404;
        return [
            'status' => 'error',
            'message' => 'Data karyawan tidak ditemukan'
        ];
    }

    // Sanitasi input dengan pengecekan null dan empty
    $nama = $karyawan->nama;
    $email = $user->email;

    // Cek dan update nama jika ada
    if (isset($data['nama']) && $data['nama'] !== null && $data['nama'] !== '') {
        $nama = $data['nama'];
    }

    // Cek dan update email jika ada
    if (isset($data['email']) && $data['email'] !== null && $data['email'] !== '') {
        $email = $data['email'];
    }

    // Cek perubahan email
    $emailChanged = false;
    if ($user->email !== $email) {
        $user->email = $email;
        $user->status = 2; // Set status menjadi 2 karena email berubah
        $emailChanged = true;
    }

    // Update nama dan email karyawan
    $karyawan->nama = $nama;
    $karyawan->email = $email;
    
    // Simpan karyawan
    if (!$karyawan->save()) {
        \Yii::$app->response->statusCode = 500;
        return [
            'status' => 'error',
            'message' => 'Gagal menyimpan data karyawan',
            'errors' => $karyawan->errors
        ];
    }

    // Set current password untuk validasi
    $user->currentPassword = $data['current_password'];

    // Update password jika ada input password baru
    if (!empty($data['new_password']) && !empty($data['confirm_password'])) {
        // Validasi password baru
        if ($data['new_password'] !== $data['confirm_password']) {
            \Yii::$app->response->statusCode = 400;
            return [
                'status' => 'error',
                'message' => 'Password baru dan konfirmasi password tidak cocok'
            ];
        }

        // Gunakan method setNewPassword
        if (!$user->setNewPassword($data['new_password'])) {
            \Yii::error('Set New Password Failed: ' . print_r($user->errors, true));
            return [
                'status' => 'error',
                'message' => 'Gagal mengubah password',
                'errors' => $user->errors
            ];
        }
    }

    // Simpan perubahan user
    if ($emailChanged || !empty($data['new_password'])) {
        if (!$user->save()) {
            \Yii::$app->response->statusCode = 500;
            return [
                'status' => 'error',
                'message' => 'Gagal menyimpan perubahan user',
                'errors' => $user->errors
            ];
        }
    }

    return [
        'status' => 'success',
        'message' => 'Profil berhasil diperbarui',
        'data' => [
            'nama' => $karyawan->nama,
            'email' => $user->email,
            'email_status' => $user->status
        ]
    ];
}



    // Aksi logout
    public function actionLogout()
    {
        // Ambil raw body
        $rawBody = \Yii::$app->request->getRawBody();
        $data = json_decode($rawBody, true);

        // Hapus token dari session
        if (isset($data['id'])) {
            \Yii::$app->session->remove('api_token_' . $data['id']);

            return [
                'status' => 'success',
                'message' => 'Logout berhasil'
            ];
        }

        \Yii::$app->response->statusCode = 400;
        return [
            'status' => 'error',
            'message' => 'User ID diperlukan'
        ];
    }
}
