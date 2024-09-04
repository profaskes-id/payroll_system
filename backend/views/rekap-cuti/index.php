<?php

use backend\models\RekapCuti;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\RekapCutiSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Rekap Cutis';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rekap-cuti-index">

    <div class="costume-container">
        <p class="">
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
                    'urlCreator' => function ($action, RekapCuti $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id_rekap_cuti' => $model->id_rekap_cuti]);
                    }
                ],

                [
                    'attribute' => 'Karyawan',
                    'value' => function ($model) {
                        return $model->karyawan->nama;
                    }
                ],

                [
                    'contentOptions' => ['style' => 'text-transform: capitalize;'],
                    'label' => 'Jenis Cuti',
                    'value' => function ($model) {
                        return $model->masterCuti->jenis_cuti;
                    }
                ],

                [
                    'label' => "Total Cuti Digunakan",
                    'value' => function ($model) {
                        return $model->total_hari_terpakai . ' Hari';
                    }
                ],
                [
                    'label' => "Jatah Cuti Tersisa",
                    'value' => function ($model) {
                        return ($model->masterCuti->total_hari_pertahun - $model->total_hari_terpakai) . ' Hari';
                    }
                ]
            ],
        ]); ?>


    </div>
</div>