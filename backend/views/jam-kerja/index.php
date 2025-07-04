<?php

use backend\models\JamKerja;
use backend\models\ShiftKerja;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\JamKerjaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Master Hari kerja';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jam-kerja-index">


    <div class="w-100">
        <div class="card card-primary card-tabs">
            <div class="p-0 pt-1 card-header">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">

                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-one-pelatihan-tab" data-toggle="pill" href="#custom-tabs-one-pelatihan" role="tab" aria-controls="custom-tabs-one-pelatihan" aria-selected="true">Jam Kerja</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-kesehatan-tab" data-toggle="pill" href="#custom-tabs-one-kesehatan" role="tab" aria-controls="custom-tabs-one-kesehatan" aria-selected="false">Shift Kerja</a>
                    </li>

                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">


                    <div class="tab-pane fade active show" id="custom-tabs-one-pelatihan" role="tabpanel" aria-labelledby="custom-tabs-one-pelatihan-tab">

                        <p class="d-flex justify-content-end " style="gap: 10px;">
                            <?= Html::a('Add new', ['/jam-kerja/create'], ['class' => 'tambah-button']) ?>
                        </p>

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
                                    'urlCreator' => function ($action, JamKerja $model, $key, $index, $column) {
                                        return Url::toRoute([$action, 'id_jam_kerja' => $model->id_jam_kerja]);
                                    }
                                ],
                                'nama_jam_kerja',
                                [
                                    'label' => 'Jenis Shift',
                                    'value' => function ($model) {
                                        return $model->jenisShift->nama_kode;
                                    }
                                ],

                            ],
                        ]); ?>

                    </div>

                    <div class="tab-pane fade" id="custom-tabs-one-kesehatan" role="tabpanel" aria-labelledby="custom-tabs-one-kesehatan-tab">

                        <p class="d-flex justify-content-end " style="gap: 10px;">
                            <?= Html::a('Add new', ['/shift-kerja/create'], ['class' => 'tambah-button']) ?>
                        </p>

                        <?= GridView::widget([
                            'dataProvider' => $dataShiftProvider,

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
                                    'urlCreator' => function ($action, ShiftKerja $model, $key, $index, $column) {
                                        return Url::toRoute(['shift-kerja/view', 'id_shift_kerja' => $model->id_shift_kerja]);
                                    }
                                ],
                                'nama_shift',
                                'jam_masuk',
                                'jam_keluar',
                            ],
                        ]); ?>
                    </div>

                </div>
            </div>

        </div>
    </div>

</div>