<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'name' => 'Admin Panel',
    'timeZone' => 'Asia/Jakarta',
    'language' => 'id_ID',
    'components' => [
        'assetManager' => [
            'bundles' => [
                'kartik\select2\Select2' => [
                    'bsDependencyEnabled' => false,
                    'bsVersion' => '5.x', // do not load bootstrap assets for a specific asset bundle
                ],
            ],
        ],
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
    ],
];
