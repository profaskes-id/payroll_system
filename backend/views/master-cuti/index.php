<?php

use backend\models\MasterCuti;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\MasterCutiSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Master Cuti';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-cuti-index">


    <div class="costume-container">
        <p lass="">
            <?= Html::a('<i class="svgIcon fa fa-regular fa-plus"></i> Add New', ['create'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <button style="width: 100%;" class="add-button" type="submit" data-toggle="collapse" data-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample">
        <i class="fas fa-search"></i>
        <span>
            Search
        </span>
    </button>
    <div style="margin-top: 10px;">
        <div class="collapse width" id="collapseWidthExample">
            <div class="" style="width: 100%;">
                <?php echo $this->render('_search', ['model' => $searchModel]); ?>
            </div>
        </div>
    </div>

    <div class='table-container'>


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
                    'urlCreator' => function ($action, MasterCuti $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id_master_cuti' => $model->id_master_cuti]);
                    }
                ],
                'jenis_cuti',
                'deskripsi_singkat:ntext',
                'total_hari_pertahun',
                [
                    'headerOptions' => ['style' => 'width: 10%; text-align: center;'],
                    'contentOptions' => ['style' => 'width: 10%; text-align: center;'],
                    'format' => 'raw',
                    'attribute' => 'status',
                    'value' => function ($model) {
                        return $model->status == 1 ? '<p class="text-success">Aktif<p>' : '<p class="text-danger">Tidak Aktif<p>';
                    }
                ]
            ],
        ]); ?>


    </div>
</div>