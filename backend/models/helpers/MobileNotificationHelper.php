<?php

namespace backend\models\helpers;

class MobileNotificationHelper
{
    public static function sendNotification($token, $title, $body, $data = [])
    {
        $apiUrl = 'https://fcm.googleapis.com/v1/projects/payroll-profaskes/messages:send';
        $accessToken = self::generateAccessToken(); // 🔥 gunakan token yang digenerate dari service account

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

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new \Exception('cURL error: ' . curl_error($ch));
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'statusCode' => $httpCode,
            'response' => json_decode($response, true),
        ];
    }

    private static function generateAccessToken()
    {
        $jsonPath = __DIR__ . '/../../../payroll-profaskes-firebase-adminsdk-fbsvc-b209234112.json';

        if (!file_exists($jsonPath)) {
            throw new \Exception("Service account file not found: $jsonPath");
        }

        $serviceAccount = json_decode(file_get_contents($jsonPath), true);

        $privateKey = $serviceAccount['private_key'];
        $clientEmail = $serviceAccount['client_email'];
        $tokenUri = $serviceAccount['token_uri'];

        $now = time();
        $jwtHeader = ['alg' => 'RS256', 'typ' => 'JWT'];
        $jwtClaimSet = [
            'iss' => $clientEmail,
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => $tokenUri,
            'iat' => $now,
            'exp' => $now + 3600,
        ];

        $base64UrlHeader = rtrim(strtr(base64_encode(json_encode($jwtHeader)), '+/', '-_'), '=');
        $base64UrlPayload = rtrim(strtr(base64_encode(json_encode($jwtClaimSet)), '+/', '-_'), '=');
        $unsignedToken = $base64UrlHeader . '.' . $base64UrlPayload;

        $signature = '';
        $privateKeyResource = openssl_pkey_get_private($privateKey);
        openssl_sign($unsignedToken, $signature, $privateKeyResource, 'sha256WithRSAEncryption');
        openssl_free_key($privateKeyResource);
        $base64UrlSignature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

        $jwt = $unsignedToken . '.' . $base64UrlSignature;

        $postData = http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]);

        $ch = curl_init($tokenUri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
        ]);

        $response = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($response, true);

        if (!isset($result['access_token'])) {
            throw new \Exception("Failed to get access token: " . json_encode($result));
        }

        return $result['access_token'];
    }
}
