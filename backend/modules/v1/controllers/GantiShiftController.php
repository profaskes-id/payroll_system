<?php

namespace app\modules\v1\controllers;

use backend\models\JamKerjaKaryawan;
use backend\models\PengajuanShift;
use yii\rest\ActiveController;
use Yii;
use backend\models\ShiftKerja;

class GantiShiftController extends ActiveController
{
  public $modelClass = 'backend\models\ShiftKerja';


  public function actionUbah()
  {
    if (Yii::$app->request->isGet) {
      $shiftKerja = $this->modelClass::find()->asArray()->all();
      return $this->asJson([
        'status' => 200,
        'data' => $shiftKerja
      ]);
    }

    if (Yii::$app->request->isPost) {
      $request = Yii::$app->request->post();
      $model = JamKerjaKaryawan::find()->where(['id_karyawan' => $request['id_karyawan']])->one();

      if (!$model) {
        return $this->asJson([
          'status' => 404,
          'message' => 'Data karyawan tidak ditemukan.'
        ]);
      }

      $model->id_shift_kerja = $request['id_shift_kerja'];

      if ($model->save()) {
        return $this->asJson([
          'status' => 200,
          'message' => 'Shift berhasil diubah.'
        ]);
      } else {
        return $this->asJson([
          'status' => 500,
          'message' => 'Gagal mengubah shift.',
          'errors' => $model->getErrors()
        ]);
      }
    }

    // Jika metode bukan GET/POST
    return $this->asJson([
      'status' => 405,
      'message' => 'Metode tidak diizinkan.'
    ]);
  }

  public function actionPengajuanShift()
  {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    if (Yii::$app->request->isGet) {
      $allDataShift = ShiftKerja::find()->asArray()->all();

      return [
        'status' => 200,
        'data' => $allDataShift
      ];
    }

    if (Yii::$app->request->isPost) {
      $model = new PengajuanShift();
      $post = Yii::$app->request->post();
      $model->id_karyawan = $post['id_karyawan'];
      $model->id_shift_kerja = $post['id_shift_kerja'];
      $model->diajukan_pada = date('Y-m-d');
      $model->status = 0;
      if ($model->save()) {
        return [
          'status' => 200,
          'message' => 'Pengajuan shift berhasil dikirim.'
        ];
      } else {
        return [
          'status' => 500,
          'message' => 'Gagal mengirim pengajuan shift.',
          'errors' => $model->getErrors()
        ];
      }
    }

    // Method tidak diizinkan
    return [
      'status' => 405,
      'message' => 'Metode tidak diizinkan.'
    ];
  }
}
