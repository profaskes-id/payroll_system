<?php

use backend\models\JamKerjaKaryawan;
use backend\models\Karyawan;
use backend\models\MasterKode;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\JamKerjaKaryawanSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Jam Kerja Karyawan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jam-kerja-karyawan-index">

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
                <?= $this->render('_search', ['model' => $searchModel]);
                ?>
            </div>
        </div>
    </div>

    <div class="table-container table-responsive">

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'summary' => false,
            'columns' => [
                [
                    'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'contentOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'class' => 'yii\grid\SerialColumn'
                ],
                [
                    'attribute' => 'Karyawan',
                    'value' => function ($model) {
                        if ($model['nama_jam_kerja']) {
                            return Html::a($model['nama'], ['jam-kerja-karyawan/view', 'id_karyawan' => $model['id_karyawan']]);
                        }
                        return Html::a($model['nama'], ['jam-kerja-karyawan/create', 'id_karyawan' => $model['id_karyawan']]);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'Jam Kerja',
                    'value' => function ($model) {
                        if (!$model['nama_jam_kerja']) {
                            return '<p class="text-danger">(Belum Diset)</p>';
                        }
                        return "{$model['nama_jam_kerja']} ({$model['nama_kode']})" ?? '<p class="text-danger">(Belum Diset)</p>';
                    },
                    'format' => 'raw'
                ],

                [
                    'attribute' => 'Maximal Terlambat',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model['max_terlambat'] != null) {
                            return date('H:i', strtotime($model['max_terlambat']));
                        }
                        return '<p class="text-danger">(Belum Diset)</p>';
                    }
                ],
            ],
        ]); ?>
    </div>


</div>