<?php

use backend\models\KategoriExpenses;
use backend\models\SubkategoriExpenses;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\KategoriExpensesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Kategori & Sub Kategori Expenses';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kategori-expenses-index">






    <div class="w-100">
        <div class="card card-primary card-tabs">
            <div class="p-0 pt-1 card-header">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">

                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-one-pelatihan-tab" data-toggle="pill" href="#custom-tabs-one-pelatihan" role="tab" aria-controls="custom-tabs-one-pelatihan" aria-selected="true">Kategori</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-kesehatan-tab" data-toggle="pill" href="#custom-tabs-one-kesehatan" role="tab" aria-controls="custom-tabs-one-kesehatan" aria-selected="false">Sub Kategori</a>
                    </li>

                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">


                    <div class="tab-pane fade active show" id="custom-tabs-one-pelatihan" role="tabpanel" aria-labelledby="custom-tabs-one-pelatihan-tab">

                        <p class="d-flex justify-content-end " style="gap: 10px;">
                            <?= Html::a('Add new', ['/kategori-expenses/create'], ['class' => 'tambah-button']) ?>
                        </p>

                        <div class="table-container">
                            <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                'columns' => [
                                    [
                                        'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                                        'contentOptions' => ['style' => 'width: 5%; text-align: center;'],
                                        'class' => 'yii\grid\SerialColumn'
                                    ],
                                    [
                                        'header' => Html::img(Yii::getAlias('@root') . '/images/icons/grid.svg', ['alt' => 'grid']),
                                        'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                                        'class' => ActionColumn::className(),
                                        'urlCreator' => function ($action, KategoriExpenses $model, $key, $index, $column) {
                                            return Url::toRoute([$action, 'id_kategori_expenses' => $model->id_kategori_expenses]);
                                        }
                                    ],
                                    'nama_kategori',
                                    'deskripsi:ntext',
                                ],
                            ]); ?>
                        </div>



                    </div>

                    <div class="tab-pane fade" id="custom-tabs-one-kesehatan" role="tabpanel" aria-labelledby="custom-tabs-one-kesehatan-tab">

                        <p class="d-flex justify-content-end " style="gap: 10px;">
                            <?= Html::a('Add new', ['/subkategori-expenses/create'], ['class' => 'tambah-button']) ?>
                        </p>

                        <?= GridView::widget([
                            'dataProvider' => $dataSubProvider,

                            'columns' => [
                                [
                                    'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                                    'contentOptions' => ['style' => 'width: 5%; text-align: center;'],
                                    'class' => 'yii\grid\SerialColumn'
                                ],
                                [
                                    'header' => Html::img(Yii::getAlias('@root') . '/images/icons/grid.svg', ['alt' => 'grid']),
                                    'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                                    'class' => ActionColumn::className(),
                                    'urlCreator' => function ($action, SubkategoriExpenses $model, $key, $index, $column) {
                                        return Url::toRoute(['/subkategori-expenses/view', 'id_subkategori_expenses' => $model->id_subkategori_expenses]);
                                    }
                                ],
                                [
                                    'label' => 'Kategori Expenses',
                                    'value' => 'kategoriExpenses.nama_kategori',
                                ],

                                'nama_subkategori',
                                'deskripsi:ntext',
                                // 'create_at',
                                //'create_by',
                                //'update_at',
                                //'update_by',
                            ],
                        ]); ?>
                    </div>

                </div>
            </div>

        </div>
    </div>


</div>