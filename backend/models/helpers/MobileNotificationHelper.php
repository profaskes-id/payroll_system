<?php

namespace backend\models\helpers;

class MobileNotificationHelper
{
    /**
     * Mengirim notifikasi ke FCM API
     *
     * @param string $token Token penerima
     * @param string $title Judul notifikasi
     * @param string $body Isi notifikasi
     * @param array $data Data tambahan (opsional)
     * @param string $bearerToken Token autentikasi Bearer
     * @return array
     * @throws \Exception
     */
    public static function sendNotification($token, $title, $body, $data = [])
    {
        $apiUrl = 'https://fcm.googleapis.com/v1/projects/payroll-profaskes/messages:send';

        // Membuat payload sesuai dengan format yang diberikan
        $payload = [
            'message' => [
                'token' => $token,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'data' => $data,
            ],
        ];

        // Menginisialisasi cURL
        $ch = curl_init($apiUrl);

        // Mengatur opsi cURL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . 'ya29.a0AeXRPp6P90PwZR3CeH9fCn1Ka9_iJrzFwjFgMe3M9oi0PlY9W-7EI3802GG-XnGPz3r2r7LDH9xnriXr1zBIo-uH4A8GSWiKnkwQrYqjFIsiE7CXEQaCHcBeXnKeXW-gSbl8QXCSRlBHlJMMstsjyBg4a7x-PRmLiDPAmjxlaCgYKAacSARMSFQHGX2MiG15ynl73h2p_kWn3o9D81g0175',
            'Content-Type: application/json',
        ]);

        // Menjalankan request dan mendapatkan response
        $response = curl_exec($ch);

        // Mengecek jika ada error
        if (curl_errno($ch)) {
            throw new \Exception('cURL error: ' . curl_error($ch));
        }

        // Mendapatkan status code
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Menutup cURL
        curl_close($ch);

        // Mengembalikan response dan status code
        return [
            'statusCode' => $httpCode,
            'response' => json_decode($response, true),
        ];
    }
}
