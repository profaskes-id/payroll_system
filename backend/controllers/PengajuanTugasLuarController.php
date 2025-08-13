<?php

namespace backend\controllers;

use backend\models\DetailTugasLuar;
use backend\models\PengajuanTugasLuar;
use backend\models\PengajuanTugasLuarSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * PengajuanTugasLuarController implements the CRUD actions for PengajuanTugasLuar model.
 */
class PengajuanTugasLuarController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all PengajuanTugasLuar models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PengajuanTugasLuarSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PengajuanTugasLuar model.
     * @param int $id_tugas_luar Id Tugas Luar
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_tugas_luar)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_tugas_luar),
        ]);
    }

    /**
     * Creates a new PengajuanTugasLuar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new PengajuanTugasLuar();
        $detailModels = [new DetailTugasLuar()]; // Default satu detail kosong

        if ($this->request->isPost) {
            $postData = $this->request->post();
            $transaction = Yii::$app->db->beginTransaction();

            try {
                // Load dan simpan model utama
                if ($model->load($postData)) {
                    $model->status_pengajuan = $model->isNewRecord ? 0 : $model->status_pengajuan;
                    $model->created_at = date('Y-m-d H:i:s');
                    $model->updated_at = date('Y-m-d H:i:s');

                    if (!$model->save()) {
                        throw new \Exception('Gagal menyimpan pengajuan tugas luar: ' . json_encode($model->errors));
                    }

                    // Proses detail jika ada
                    if (isset($postData['DetailTugasLuar']) && is_array($postData['DetailTugasLuar'])) {
                        // Delete existing details if editing
                        if (!$model->isNewRecord) {
                            DetailTugasLuar::deleteAll(['id_tugas_luar' => $model->id_tugas_luar]);
                        }

                        $detailDataToInsert = [];
                        $urutan = 1;

                        foreach ($postData['DetailTugasLuar'] as $detailData) {
                            // Validasi data detail
                            if (empty($detailData['keterangan'])) {
                                throw new \Exception('Keterangan detail tidak boleh kosong');
                            }

                            $detailDataToInsert[] = [
                                'id_tugas_luar' => $model->id_tugas_luar,
                                'keterangan' => $detailData['keterangan'],
                                'jam_diajukan' => $detailData['jam_diajukan'] ?? null,
                                'status_pengajuan_detail' => $detailData['status_pengajuan_detail'] ?? 1,
                                'status_check' => 0,
                                'urutan' => $urutan++,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                            ];
                        }

                        // Gunakan batch insert untuk efisiensi
                        $batchInsertCount = Yii::$app->db->createCommand()
                            ->batchInsert(
                                DetailTugasLuar::tableName(),
                                [
                                    'id_tugas_luar',
                                    'keterangan',
                                    'jam_diajukan',
                                    'status_pengajuan_detail',
                                    'status_check',
                                    'urutan',
                                    'created_at',
                                    'updated_at'
                                ],
                                $detailDataToInsert
                            )
                            ->execute();

                        if ($batchInsertCount !== count($detailDataToInsert)) {
                            throw new \Exception('Gagal menyimpan semua detail tugas');
                        }

                        // Validasi minimal ada 1 detail
                        if (empty($detailDataToInsert)) {
                            throw new \Exception('Setidaknya harus ada satu lokasi tugas');
                        }
                    } else {
                        throw new \Exception('Data detail tugas tidak ditemukan');
                    }

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Data berhasil disimpan');
                    return $this->redirect(['view', 'id_tugas_luar' => $model->id_tugas_luar]);
                } else {
                    throw new \Exception('Gagal memproses data pengajuan');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('danger', $e->getMessage());

                // Kembalikan data yang sudah diinput untuk ditampilkan kembali
                if (isset($postData['DetailTugasLuar'])) {
                    $detailModels = [];
                    foreach ($postData['DetailTugasLuar'] as $detailData) {
                        $detailModel = new DetailTugasLuar();
                        $detailModel->attributes = $detailData;
                        $detailModels[] = $detailModel;
                    }
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'detailModels' => $detailModels,
        ]);
    }



    /**
     * Updates an existing PengajuanTugasLuar model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_tugas_luar Id Tugas Luar
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_tugas_luar)
    {
        $model = $this->findModel($id_tugas_luar);
        $detailModels = $model->detailTugasLuars;

        if (empty($detailModels)) {
            $detailModels = [new DetailTugasLuar()];
        }

        if ($this->request->isPost) {
            $postData = $this->request->post();
            $transaction = Yii::$app->db->beginTransaction();

            try {
                // Load and save main model
                if ($model->load($postData)) {
                    $model->updated_at = date('Y-m-d H:i:s');
                    $model->updated_by = Yii::$app->user->identity->id;
                    if (!$model->save()) {
                        throw new \Exception('Gagal menyimpan pengajuan: ' . json_encode($model->errors));
                    }

                    // Process detail data
                    $details = $postData['DetailTugasLuar'] ?? [];

                    // Get existing detail IDs
                    $existingDetails = DetailTugasLuar::find()
                        ->where(['id_tugas_luar' => $model->id_tugas_luar])
                        ->indexBy('id_detail')
                        ->all();

                    $savedDetails = [];
                    $urutan = 1;

                    foreach ($details as $detailData) {
                        if (!empty($detailData['id_detail']) && isset($existingDetails[$detailData['id_detail']])) {
                            // Update existing detail
                            $detail = $existingDetails[$detailData['id_detail']];
                            unset($existingDetails[$detailData['id_detail']]); // Remove from delete list
                        } else {
                            // Create new detail
                            $detail = new DetailTugasLuar();
                            $detail->id_tugas_luar = $model->id_tugas_luar;
                            $detail->created_at = date('Y-m-d H:i:s');
                        }

                        // Set default values if not provided
                        $detailData['status_check'] = $detailData['status_check'] ?? 0;
                        $detailData['status_pengajuan_detail'] = $detailData['status_pengajuan_detail'] ?? 1;
                        $detailData['urutan'] = $urutan++;

                        $detail->updated_at = date('Y-m-d H:i:s');

                        if (!$detail->load($detailData, '') || !$detail->save()) {
                            throw new \Exception(
                                'Gagal menyimpan detail: ' . json_encode($detail->errors) .
                                    ' Data: ' . json_encode($detailData)
                            );
                        }

                        $savedDetails[] = $detail;
                    }

                    // Delete details that were removed
                    if (!empty($existingDetails)) {
                        DetailTugasLuar::deleteAll(['id_detail' => array_keys($existingDetails)]);
                    }

                    // Validate at least one detail exists
                    if (empty($savedDetails)) {
                        throw new \Exception('Setidaknya harus ada satu detail tugas');
                    }

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Data berhasil diperbarui');
                    return $this->redirect(['view', 'id_tugas_luar' => $model->id_tugas_luar]);
                } else {
                    throw new \Exception('Gagal memproses data pengajuan');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('danger', 'Gagal menyimpan: ' . $e->getMessage());

                // Reload detail models for form
                $detailModels = [];
                foreach ($postData['DetailTugasLuar'] as $detailData) {
                    $detail = new DetailTugasLuar();
                    $detail->attributes = $detailData;
                    $detailModels[] = $detail;
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'detailModels' => $detailModels,
        ]);
    }
    /**
     * Deletes an existing PengajuanTugasLuar model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_tugas_luar Id Tugas Luar
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_tugas_luar)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = $this->findModel($id_tugas_luar);

            foreach ($model->detailTugasLuars as $detail) {
                if (!empty($detail->bukti_foto)) {
                    $filePath = Yii::getAlias('@webroot/uploads/bukti_tugas_luar/') . $detail->bukti_foto;
                    if (file_exists($filePath) && is_file($filePath) && !unlink($filePath)) {
                        throw new \Exception("Gagal menghapus file: " . $detail->bukti_foto);
                    }
                }
            }

            if (!$model->delete()) {
                throw new \Exception("Gagal menghapus record tugas luar");
            }

            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    public function actionDeleteDetail($id)
    {
        $model = DetailTugasLuar::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('Detail tugas luar tidak ditemukan.');
        }

        // Simpan id_tugas_luar untuk redirect
        $id_tugas_luar = $model->id_tugas_luar;

        // Hapus file foto jika ada
        if (!empty($model->bukti_foto)) {
            $filePath = Yii::getAlias('@webroot/uploads/bukti_tugas_luar/') . $model->bukti_foto;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Hapus record dari database
        $model->delete();

        Yii::$app->session->setFlash('success', 'Detail tugas luar berhasil dihapus.');
        return $this->redirect(['view', 'id_tugas_luar' => $id_tugas_luar]);
    }

    /**
     * Finds the PengajuanTugasLuar model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_tugas_luar Id Tugas Luar
     * @return PengajuanTugasLuar the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_tugas_luar)
    {
        if (($model = PengajuanTugasLuar::findOne(['id_tugas_luar' => $id_tugas_luar])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
