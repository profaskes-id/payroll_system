<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
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
            // 'baseUrl' => 'https://profaskes.id',
            'baseUrl' => 'https://localhost:8080',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
    ],
    'params' => $params,
];
