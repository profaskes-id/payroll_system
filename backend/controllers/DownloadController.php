<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;

class DownloadController extends Controller
{
    public function actionIndex()
    {
        $filePath = Yii::getAlias('@webroot/apk/application-24136a5f-a7b9-4941-b823-4333198ab285.apk');

        // Cek apakah file ada
        if (!file_exists($filePath)) {
            throw new NotFoundHttpException('File tidak ditemukan.');
        }

        // Set header untuk download file
        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', 'application/vnd.android.package-archive');
        Yii::$app->response->headers->add('Content-Disposition', 'attachment; filename="payroll-mobile-V1.0.0.apk"');
        Yii::$app->response->headers->add('Content-Length', filesize($filePath));

        // Baca file dan kirim ke output
        return Yii::$app->response->sendFile($filePath);
    }
}
