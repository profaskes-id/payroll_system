<?php

use backend\models\Bagian;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\Perusahaan $model */

$this->title = $model->nama_perusahaan;
$this->params['breadcrumbs'][] = ['label' => 'perusahaan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="perusahaan-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class='table-container'>


        <div class="w-100">
            <div class="card card-primary card-tabs">
                <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Perusahaan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Bagian</a>
                        </li>

                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                            <p class="d-flex justify-content-end " style="gap: 10px;">
                                <?= Html::a('Update', ['update', 'id_perusahaan' => $model->id_perusahaan], ['class' => 'add-button']) ?>
                                <?= Html::a('Delete', ['delete', 'id_perusahaan' => $model->id_perusahaan], [
                                    'class' => 'reset-button',
                                    'data' => [
                                        'confirm' => 'Are you sure you want to delete this item?',
                                        'method' => 'post',
                                    ],
                                ]) ?>
                            </p>

                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    'id_perusahaan',
                                    'nama_perusahaan',
                                    [
                                        'label' => 'Status Perusahaan',
                                        'value' => function ($model) {
                                            return $model->statusPerusahaan->nama_kode;
                                        }
                                    ],
                                ],
                            ]) ?>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                            <p class="d-flex justify-content-end " style="gap: 10px;">
                                <?= Html::a('Add new', ['/bagian/create'], ['class' => 'tambah-button']) ?>
                            </p>
                            <?= GridView::widget([
                                'dataProvider' => $perusahaanProvider,
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
                                        'urlCreator' => function ($action, Bagian $model, $key, $index, $column) {
                                            return Url::toRoute(['/bagian/view', 'id_bagian' => $model->id_bagian]);
                                        }
                                    ],
                                    'nama_bagian',

                             
                                ],
                            ]); ?>
                        </div>

                    </div>
                </div>

            </div>
        </div>







    </div>

</div>