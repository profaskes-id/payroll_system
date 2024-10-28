<?php

use backend\models\JamKerja;
use backend\models\TotalHariKerja;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\TotalHariKerja $model */


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

$id_jamkerja_byget = Yii::$app->request->get('id_jam_kerja');
$jamkerja = JamKerja::find()->where(['id_jam_kerja' => $id_jamkerja_byget])->one();
$this->title = "Total Hari Kerja - " . $jamkerja['nama_jam_kerja'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Total Hari Kerja'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<div class="total-hari-kerja-view">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <div class='table-container'>
        <br>
        <div class="d-flex align-items-center justify-content-between">
            <p class="d-flex justify-content-end " style="gap: 10px;">
                <?= Html::a('Add new', ['/total-hari-kerja/create', 'id_jam_kerja' => $id_jamkerja_byget], ['class' => 'tambah-button']) ?>
            </p>
        </div>
        <?= GridView::widget(
            [
                'dataProvider' => $model,
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
                        'urlCreator' => function ($patam, $model, $key, $index, $column) {

                            return Url::toRoute(['total-hari-kerja/detail', 'id_total_hari_kerja' => $model['id_total_hari_kerja']]);
                        }
                    ],
                    [
                        'attribute' => 'Total Hari',
                        'headerOptions' => ['style' => 'width: 100px; text-align: center;'],
                        'contentOptions' => ['style' => 'width: 100px; text-align: center;'],
                        'value' => function ($model) {
                            return $model['total_hari'];
                        }
                    ],
                    [
                        'headerOptions' => ['style' => 'width: 100px; text-align: center;'],
                        'contentOptions' => ['style' => 'width: 100px; text-align: center;'],
                        'attribute' => 'bulan',
                        'value' => function ($model) use ($months) {
                            return $months[$model['bulan'] - 1];
                        }
                    ],
                    [
                        'attribute' => 'tahun',
                        'headerOptions' => ['style' => 'width: 100px; text-align: center;'],
                        'contentOptions' => ['style' => 'width: 100px; text-align: center;'],
                        'value' => function ($model) {
                            return $model['tahun'];
                        }
                    ],

                    [
                        'headerOptions' => ['style' => 'width: 100px; text-align: center;'],
                        'contentOptions' => ['style' => 'width: 100px; text-align: center;'],
                        'attribute' => 'status',
                        'format' => 'raw',
                        'value' => function ($model) {
                            if ($model['is_aktif'] == 1) {
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