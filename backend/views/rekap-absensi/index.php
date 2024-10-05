<?php

use backend\models\Absensi;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\AbsensiSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Rekap Absensi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="absensi-index position-relative">
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



    <div class="table-container table-responsive">
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
                    'urlCreator' => function ($action, Absensi $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id_absensi' => $model->id_absensi]);
                    }
                ],
                [
                    'attribute' => 'Karyawan',
                    'value' => function ($model) {
                        return $model->karyawan->nama;
                    }
                ],
                [
                    'headerOptions' => ['style' => ' text-align: center;'],
                    'contentOptions' => ['style' => ' text-align: center;'],
                    'label' => 'Tgl. Absen',
                    'value' => function ($model) {
                        return date('d-m-Y', strtotime($model->tanggal));
                    }
                ],
                [
                    'headerOptions' => ['style' => ' text-align: center;'],
                    'contentOptions' => ['style' => ' text-align: center;'],
                    'label' => 'Status Hadir',
                    'value' => function ($model) {
                        return $model->statusHadir->nama_kode;
                    }
                ],

            ],
        ]); ?>
    </div>



</div>