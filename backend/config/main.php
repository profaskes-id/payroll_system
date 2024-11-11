<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'name' => 'Admin Panel',
    'timeZone' => 'Asia/Jakarta',
    'language' => 'id_ID',
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'user' => [
            'class' => 'amnah\yii2\user\Module',
        ],
    ],
    'components' => [
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'currencyCode' => 'IDR', // Kode mata uang untuk Indonesia
            'decimalSeparator' => ',', // Pemisah desimal
            'thousandSeparator' => '.', // Pemisah ribuan
        ],
        'mpdf' => [
            'class' => '\kartik\mpdf\Pdf',
            'format' => 'A4-L',
            'orientation' => 'L',
            'destination' => 'D',
            'methods' => [
                'SetHeader' => [''],
                'SetFooter' => [''],
            ],
        ],
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'class' => 'amnah\yii2\user\components\User',
        ],
        'session' => [
            // auth ini akan di simapan , jadi sama kan frontend dengan ini
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [],
        ],
        'urlManagerFrontEnd' => [
            'class' => 'yii\web\urlManager',
            // 'baseUrl' => 'https://payroll.profaskes.id',
            'baseUrl' => 'https://localhost:8000',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],

    ],
    'params' => $params,
];
