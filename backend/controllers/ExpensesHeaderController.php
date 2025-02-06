<?php

namespace backend\controllers;

use backend\models\ExpensesHeader;
use backend\models\ExpensesHeaderSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ExpensesHeaderController implements the CRUD actions for ExpensesHeader model.
 */
class ExpensesHeaderController extends Controller
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
                'access' => [
                    'class' => \yii\filters\AccessControl::class,
                    'rules' => [

                        [
                            'allow' => true,
                            'roles' => ['@'], // Allow authenticated users
                            'matchCallback' => function ($rule, $action) {
                                $user = Yii::$app->user;
                                // Check if the user does  have the 'admin' or 'super admin' role
                                return $user->can('admin') && $user->can('super_admin');
                            },
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all ExpensesHeader models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ExpensesHeaderSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ExpensesHeader model.
     * @param int $id_expense_header Id Expense Header
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_expense_header)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_expense_header),
        ]);
    }

    /**
     * Creates a new ExpensesHeader model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ExpensesHeader();

        if ($model->load($this->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Data berhasil disimpan.');
                return $this->redirect(['view', 'id_expense_header' => $model->id_expense_header]);
            } else {
                Yii::$app->session->setFlash('error', 'Data gagal disimpan.');
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ExpensesHeader model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_expense_header Id Expense Header
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_expense_header)
    {
        $model = $this->findModel($id_expense_header);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_expense_header' => $model->id_expense_header]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ExpensesHeader model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_expense_header Id Expense Header
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_expense_header)
    {
        $this->findModel($id_expense_header)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ExpensesHeader model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_expense_header Id Expense Header
     * @return ExpensesHeader the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_expense_header)
    {
        if (($model = ExpensesHeader::findOne(['id_expense_header' => $id_expense_header])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
