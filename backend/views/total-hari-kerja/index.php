<?php

use backend\models\TotalHariKerja;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\TotalHariKerjaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$months = [
    'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember',
];
$this->title = Yii::t('app', 'Total Hari Kerja');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="total-hari-kerja-index">

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


    <div class='table-container'>
        <?= GridView::widget(
            [
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
                        'urlCreator' => function ($action, TotalHariKerja $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id_total_hari_kerja' => $model->id_total_hari_kerja]);
                        }
                    ],
                    [
                        'label' => 'Jam Kerja',
                        'value' => function ($model) {
                            return  $model->jamKerja->nama_jam_kerja;
                        }
                    ],
                    [
                        'attribute' => 'Total Hari',
                        'headerOptions' => ['style' => 'width: 100px; text-align: center;'],
                        'contentOptions' => ['style' => 'width: 100px; text-align: center;'],
                        'value' => function ($model) {
                            return $model->total_hari;
                        }
                    ],
                    [
                        'headerOptions' => ['style' => 'width: 100px; text-align: center;'],
                        'contentOptions' => ['style' => 'width: 100px; text-align: center;'],
                        'attribute' => 'bulan',
                        'value' => function ($model) use ($months) {
                            return $months[$model->bulan - 1];
                        }
                    ],
                    [
                        'attribute' => 'tahun',
                        'headerOptions' => ['style' => 'width: 100px; text-align: center;'],
                        'contentOptions' => ['style' => 'width: 100px; text-align: center;'],
                        'value' => function ($model) {
                            return $model->tahun;
                        }
                    ],

                    [
                        'headerOptions' => ['style' => 'width: 100px; text-align: center;'],
                        'contentOptions' => ['style' => 'width: 100px; text-align: center;'],
                        'attribute' => 'status',
                        'format' => 'raw',
                        'value' => function ($model) {
                            if ($model->is_aktif == 1) {
                                return "<span class='text-success'>Aktif</span>";
                            } else {
                                return "<span class='text-danger'>Tidak Aktif</span>";
                            }
                        }



                    ],
                ],
            ],
        ); ?>
    </div>


</div>