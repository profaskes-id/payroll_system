<?php

namespace backend\controllers;

use backend\models\SubkategoriExpenses;
use backend\models\SubkategoriExpensesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * SubkategoriExpensesController implements the CRUD actions for SubkategoriExpenses model.
 */
class SubkategoriExpensesController extends Controller
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
     * Lists all SubkategoriExpenses models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SubkategoriExpensesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SubkategoriExpenses model.
     * @param int $id_subkategori_expenses Id Subkategori Expenses
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_subkategori_expenses)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_subkategori_expenses),
        ]);
    }

    /**
     * Creates a new SubkategoriExpenses model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new SubkategoriExpenses();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['/kategori-expenses/index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SubkategoriExpenses model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_subkategori_expenses Id Subkategori Expenses
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_subkategori_expenses)
    {
        $model = $this->findModel($id_subkategori_expenses);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['/kategori-expenses/index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SubkategoriExpenses model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_subkategori_expenses Id Subkategori Expenses
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_subkategori_expenses)
    {
        $this->findModel($id_subkategori_expenses)->delete();

        return $this->redirect(['/kategori-expenses/index']);
    }

    /**
     * Finds the SubkategoriExpenses model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_subkategori_expenses Id Subkategori Expenses
     * @return SubkategoriExpenses the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_subkategori_expenses)
    {
        if (($model = SubkategoriExpenses::findOne(['id_subkategori_expenses' => $id_subkategori_expenses])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
