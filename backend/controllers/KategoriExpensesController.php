<?php

namespace backend\controllers;

use backend\models\KategoriExpenses;
use backend\models\KategoriExpensesSearch;
use backend\models\SubkategoriExpensesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * KategoriExpensesController implements the CRUD actions for KategoriExpenses model.
 */
class KategoriExpensesController extends Controller
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
     * Lists all KategoriExpenses models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new KategoriExpensesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $searchSubModel = new SubkategoriExpensesSearch();
        $dataSubProvider = $searchSubModel->search($this->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'searchSubModel' => $searchSubModel,
            'dataSubProvider' => $dataSubProvider
        ]);
    }

    /**
     * Displays a single KategoriExpenses model.
     * @param int $id_kategori_expenses Id Kategori Expenses
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_kategori_expenses)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_kategori_expenses),
        ]);
    }

    /**
     * Creates a new KategoriExpenses model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new KategoriExpenses();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_kategori_expenses' => $model->id_kategori_expenses]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing KategoriExpenses model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_kategori_expenses Id Kategori Expenses
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_kategori_expenses)
    {
        $model = $this->findModel($id_kategori_expenses);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_kategori_expenses' => $model->id_kategori_expenses]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing KategoriExpenses model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_kategori_expenses Id Kategori Expenses
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_kategori_expenses)
    {
        $this->findModel($id_kategori_expenses)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the KategoriExpenses model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_kategori_expenses Id Kategori Expenses
     * @return KategoriExpenses the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_kategori_expenses)
    {
        if (($model = KategoriExpenses::findOne(['id_kategori_expenses' => $id_kategori_expenses])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
